<?php

namespace App\Http\Controllers;

use App\Models\Cpmk;
use App\Models\Cpl;
use App\Models\MataKuliahSemester;
use App\Models\MataKuliahKurikulum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // If user hasn't chosen an active role yet, redirect to choose-role page
        if (! session()->has('active_role')) {
            return redirect()->route('auth.choose-role');
        }

        addVendors(['amcharts', 'amcharts-maps', 'amcharts-stock']);
        return view('pages/dashboards.index');
    }

    public function getStats(Request $request): JsonResponse
    {
    // Filter by active prodi if set in session
    $prodiId = session('prodi_id');

        // Get semester filter parameter or set 'all' as default
        $semester = $request->input('semester', 'all');

        // Get all unique tahun and semester combinations for the filter
        $semesterOptionsQuery = MataKuliahSemester::select('tahun', 'semester')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->orderBy('semester', 'desc');

        if ($prodiId) {
            // scope by prodi through mkk->kurikulum
            $semesterOptionsQuery->whereHas('mkk', function ($q) use ($prodiId) {
                $q->whereHas('kurikulum', function ($q2) use ($prodiId) {
                    $q2->where('prodi_id', $prodiId);
                });
            });
        }

        $semesterOptions = $semesterOptionsQuery->get()->map(function ($item) {
            return [
                'id' => $item->tahun . '-' . $item->semester,
                'text' => 'Semester ' . $item->tahun . '/' . $item->semester
            ];
        });

        // Get current active tahun and semester for display
        $latestSemesterQuery = MataKuliahSemester::select('tahun', 'semester')
            ->orderBy('tahun', 'desc')
            ->orderBy('semester', 'desc');

        if ($prodiId) {
            $latestSemesterQuery->whereHas('mkk', function ($q) use ($prodiId) {
                $q->whereHas('kurikulum', function ($q2) use ($prodiId) {
                    $q2->where('prodi_id', $prodiId);
                });
            });
        }

        $latestSemester = $latestSemesterQuery->first();

        $tahunAktif = $latestSemester ? $latestSemester->tahun : date('Y');
        $semesterAktif = $latestSemester ? $latestSemester->semester : 1;

        // Initialize query builder
        $mksQuery = MataKuliahSemester::query();

        if ($prodiId) {
            $mksQuery->whereHas('mkk', function ($q) use ($prodiId) {
                $q->whereHas('kurikulum', function ($q2) use ($prodiId) {
                    $q2->where('prodi_id', $prodiId);
                });
            });
        }

        // Apply semester filter if not 'all'
        if ($semester !== 'all' && strpos($semester, '-') !== false) {
            list($tahun, $semesterNum) = explode('-', $semester);
            $mksQuery->where('tahun', $tahun)
                    ->where('semester', $semesterNum);
        }

        // Get MKS count
        $mksCount = $mksQuery->count();

        // Get CPMK count for selected semester(s)
        $cpmkCount = Cpmk::whereHas('mks', function ($query) use ($semester, $prodiId) {
            if ($semester !== 'all' && strpos($semester, '-') !== false) {
                list($tahun, $semesterNum) = explode('-', $semester);
                $query->where('tahun', $tahun)
                      ->where('semester', $semesterNum);
            }

            if ($prodiId) {
                $query->whereHas('mkk', function ($q) use ($prodiId) {
                    $q->whereHas('kurikulum', function ($q2) use ($prodiId) {
                        $q2->where('prodi_id', $prodiId);
                    });
                });
            }
        })->count();

        // Get CPL count for selected semester(s)
        $cplCount = Cpl::whereHas('cpmks.mks', function ($query) use ($semester, $prodiId) {
            if ($semester !== 'all' && strpos($semester, '-') !== false) {
                list($tahun, $semesterNum) = explode('-', $semester);
                $query->where('tahun', $tahun)
                      ->where('semester', $semesterNum);
            }

            if ($prodiId) {
                $query->whereHas('mkk', function ($q) use ($prodiId) {
                    $q->whereHas('kurikulum', function ($q2) use ($prodiId) {
                        $q2->where('prodi_id', $prodiId);
                    });
                });
            }
        })->count();

        // Get CPMK distribution per CPL for selected semester(s)
        $cpmkCplDistributionQuery = DB::table('mst_cpl')
            ->select('mst_cpl.nomor as cpl', DB::raw('COUNT(DISTINCT trx_cpmk_cpl.cpmk_id) as count'))
            ->leftJoin('trx_cpmk_cpl', 'mst_cpl.id', '=', 'trx_cpmk_cpl.cpl_id')
            ->leftJoin('mst_cpmk', 'trx_cpmk_cpl.cpmk_id', '=', 'mst_cpmk.id')
            ->leftJoin('mst_mata_kuliah_semester', 'mst_cpmk.mks_id', '=', 'mst_mata_kuliah_semester.id');

        if ($semester !== 'all' && strpos($semester, '-') !== false) {
            list($tahun, $semesterNum) = explode('-', $semester);
            $cpmkCplDistributionQuery->where('mst_mata_kuliah_semester.tahun', $tahun)
                                     ->where('mst_mata_kuliah_semester.semester', $semesterNum);
        }

        if ($prodiId) {
            // mst_cpl -> kurikulum -> prodi
            $cpmkCplDistributionQuery->leftJoin('mst_kurikulum', 'mst_cpl.kurikulum_id', '=', 'mst_kurikulum.id')
                ->where('mst_kurikulum.prodi_id', $prodiId);
        }

        $cpmkCplDistribution = $cpmkCplDistributionQuery->groupBy('mst_cpl.nomor')
                                                        ->orderBy('mst_cpl.nomor')
                                                        ->get();

        // Get curriculum distribution (mata kuliah per semester)
        $curriculumDistributionQuery = DB::table('mst_mata_kuliah_kurikulum')
            ->select('mst_mata_kuliah_semester.tahun', 'mst_mata_kuliah_semester.semester as semester_num', DB::raw('COUNT(*) as count'))
            ->join('mst_mata_kuliah_semester', 'mst_mata_kuliah_kurikulum.id', '=', 'mst_mata_kuliah_semester.mkk_id');

        // Apply semester filter if not 'all'
        if ($semester !== 'all' && strpos($semester, '-') !== false) {
            list($tahun, $semesterNum) = explode('-', $semester);
            $curriculumDistributionQuery->where('mst_mata_kuliah_semester.tahun', $tahun)
                                       ->where('mst_mata_kuliah_semester.semester', $semesterNum);
        }

        if ($prodiId) {
            // mst_mata_kuliah_kurikulum -> kurikulum -> prodi
            $curriculumDistributionQuery->leftJoin('mst_kurikulum', 'mst_mata_kuliah_kurikulum.kurikulum_id', '=', 'mst_kurikulum.id')
                ->where('mst_kurikulum.prodi_id', $prodiId);
        }

        $curriculumDistribution = $curriculumDistributionQuery->groupBy('mst_mata_kuliah_semester.tahun', 'mst_mata_kuliah_semester.semester')
                                                            ->orderBy('mst_mata_kuliah_semester.tahun')
                                                            ->orderBy('mst_mata_kuliah_semester.semester')
                                                            ->get()
                                                            ->map(function ($item) {
                                                                return [
                                                                    'semester' => $item->tahun . '-' . $item->semester_num,
                                                                    'count' => $item->count
                                                                ];
                                                            });

        // Get MKS list with CPMK and CPL counts
        $mksListQuery = clone $mksQuery;
        $mksList = $mksListQuery->with(['mkk'])
            ->get()
            ->map(function ($mks) {
                $cpmkCount = Cpmk::where('mks_id', $mks->id)->count();
                $cplCount = DB::table('trx_cpmk_cpl')
                    ->join('mst_cpmk', 'trx_cpmk_cpl.cpmk_id', '=', 'mst_cpmk.id')
                    ->where('mst_cpmk.mks_id', $mks->id)
                    ->distinct('trx_cpmk_cpl.cpl_id')
                    ->count();

                // Count unique classes (using kelas field from nilai table)
                $kelasCount = DB::table('trx_nilai')
                    ->where('mks_id', $mks->id)
                    ->distinct('kelas')
                    ->count('kelas');

                // Count unique students for this course
                $mahasiswaCount = DB::table('trx_nilai')
                    ->where('mks_id', $mks->id)
                    ->distinct('mahasiswa_id')
                    ->count('mahasiswa_id');

                return [
                    'id' => $mks->id,
                    'kode' => $mks->mkk->kode,
                    'nama' => $mks->mkk->nama,
                    'sks' => $mks->mkk->sks,
                    'tahun' => $mks->tahun,
                    'semester' => $mks->semester,
                    'cpmks_count' => $cpmkCount,
                    'cpls_count' => $cplCount,
                    'kelas_count' => $kelasCount,
                    'mahasiswa_count' => $mahasiswaCount
                ];
            });

        return response()->json([
            'tahun_aktif' => $tahunAktif,
            'semester_aktif' => $semesterAktif,
            'semester_options' => $semesterOptions,
            'cpmk_count' => $cpmkCount,
            'cpl_count' => $cplCount,
            'mks_count' => $mksCount,
            'cpmk_cpl_distribution' => $cpmkCplDistribution,
            'curriculum_distribution' => $curriculumDistribution,
            'mks_list' => $mksList
        ]);
    }
}
