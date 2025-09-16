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

        // Get kurikulum filter parameter or set 'all' as default
        $kurikulum = $request->input('kurikulum', 'all');

        // Get all kurikulum for filter dropdown
        $kurikulumOptionsQuery = \App\Models\Kurikulum::query();
        if ($prodiId) {
            $kurikulumOptionsQuery->where('prodi_id', $prodiId);
        }
        $kurikulumOptions = $kurikulumOptionsQuery->orderBy('nama')->get(['id', 'nama']);

        // Get semester filter parameter or set 'all' as default
        $semester = $request->input('semester', 'all');

        // Get all unique tahun and semester combinations for the filter
        $semesterOptionsQuery = MataKuliahSemester::select('tahun', 'semester')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->orderBy('semester', 'desc');

        if ($prodiId || $kurikulum !== 'all') {
            $semesterOptionsQuery->whereHas('mkk', function ($q) use ($prodiId, $kurikulum) {
                $q->whereHas('kurikulum', function ($q2) use ($prodiId, $kurikulum) {
                    if ($prodiId) $q2->where('prodi_id', $prodiId);
                    if ($kurikulum !== 'all') $q2->where('id', $kurikulum);
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

        if ($prodiId || $kurikulum !== 'all') {
            $latestSemesterQuery->whereHas('mkk', function ($q) use ($prodiId, $kurikulum) {
                $q->whereHas('kurikulum', function ($q2) use ($prodiId, $kurikulum) {
                    if ($prodiId) $q2->where('prodi_id', $prodiId);
                    if ($kurikulum !== 'all') $q2->where('id', $kurikulum);
                });
            });
        }

        $latestSemester = $latestSemesterQuery->first();

        $tahunAktif = $latestSemester ? $latestSemester->tahun : date('Y');
        $semesterAktif = $latestSemester ? $latestSemester->semester : 1;

        // Initialize query builder
        $mksQuery = MataKuliahSemester::query();

        if ($prodiId || $kurikulum !== 'all') {
            $mksQuery->whereHas('mkk', function ($q) use ($prodiId, $kurikulum) {
                $q->whereHas('kurikulum', function ($q2) use ($prodiId, $kurikulum) {
                    if ($prodiId) $q2->where('prodi_id', $prodiId);
                    if ($kurikulum !== 'all') $q2->where('id', $kurikulum);
                });
            });
        }

        // Apply semester filter if not 'all'
        if ($semester !== 'all' && strpos($semester, '-') !== false) {
            list($tahun, $semesterNum) = explode('-', $semester);
            $mksQuery->where('tahun', $tahun)
                    ->where('semester', $semesterNum);
        }


    // Get MKS count (Total Mata Kuliah Semester)
    $mksCount = $mksQuery->count();

    // Get MKS Aktif count (Makul SMT Aktif: yang sudah ada nilainya di trx_nilai)
    $mksAktifQuery = clone $mksQuery;
    $mksAktifCount = $mksAktifQuery->whereHas('nilai')->count();

        // Get CPMK count for selected semester(s)
        $cpmkCount = Cpmk::whereHas('mks', function ($query) use ($semester, $prodiId, $kurikulum) {
            if ($semester !== 'all' && strpos($semester, '-') !== false) {
                list($tahun, $semesterNum) = explode('-', $semester);
                $query->where('tahun', $tahun)
                      ->where('semester', $semesterNum);
            }
            if ($prodiId || $kurikulum !== 'all') {
                $query->whereHas('mkk', function ($q) use ($prodiId, $kurikulum) {
                    $q->whereHas('kurikulum', function ($q2) use ($prodiId, $kurikulum) {
                        if ($prodiId) $q2->where('prodi_id', $prodiId);
                        if ($kurikulum !== 'all') $q2->where('id', $kurikulum);
                    });
                });
            }
        })->count();

        // Get CPL count for selected semester(s)
        $cplCount = Cpl::whereHas('cpmks.mks', function ($query) use ($semester, $prodiId, $kurikulum) {
            if ($semester !== 'all' && strpos($semester, '-') !== false) {
                list($tahun, $semesterNum) = explode('-', $semester);
                $query->where('tahun', $tahun)
                      ->where('semester', $semesterNum);
            }
            if ($prodiId || $kurikulum !== 'all') {
                $query->whereHas('mkk', function ($q) use ($prodiId, $kurikulum) {
                    $q->whereHas('kurikulum', function ($q2) use ($prodiId, $kurikulum) {
                        if ($prodiId) $q2->where('prodi_id', $prodiId);
                        if ($kurikulum !== 'all') $q2->where('id', $kurikulum);
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

        if ($prodiId || $kurikulum !== 'all') {
            $cpmkCplDistributionQuery->leftJoin('mst_kurikulum', 'mst_cpl.kurikulum_id', '=', 'mst_kurikulum.id');
            if ($prodiId) $cpmkCplDistributionQuery->where('mst_kurikulum.prodi_id', $prodiId);
            if ($kurikulum !== 'all') $cpmkCplDistributionQuery->where('mst_kurikulum.id', $kurikulum);
        }

        $cpmkCplDistribution = $cpmkCplDistributionQuery->groupBy('mst_cpl.nomor')
                                                        ->orderBy('mst_cpl.nomor')
                                                        ->get();

        // Get curriculum distribution (total makul per semester)
        $curriculumDistributionQuery = DB::table('mst_mata_kuliah_kurikulum')
            ->select('mst_mata_kuliah_semester.tahun', 'mst_mata_kuliah_semester.semester as semester_num', DB::raw('COUNT(*) as count'))
            ->join('mst_mata_kuliah_semester', 'mst_mata_kuliah_kurikulum.id', '=', 'mst_mata_kuliah_semester.mkk_id');

        // Apply semester filter if not 'all'
        if ($semester !== 'all' && strpos($semester, '-') !== false) {
            list($tahun, $semesterNum) = explode('-', $semester);
            $curriculumDistributionQuery->where('mst_mata_kuliah_semester.tahun', $tahun)
                                       ->where('mst_mata_kuliah_semester.semester', $semesterNum);
        }

        if ($prodiId || $kurikulum !== 'all') {
            $curriculumDistributionQuery->leftJoin('mst_kurikulum', 'mst_mata_kuliah_kurikulum.kurikulum_id', '=', 'mst_kurikulum.id');
            if ($prodiId) $curriculumDistributionQuery->where('mst_kurikulum.prodi_id', $prodiId);
            if ($kurikulum !== 'all') $curriculumDistributionQuery->where('mst_kurikulum.id', $kurikulum);
        }

        $curriculumDistributionRaw = $curriculumDistributionQuery->groupBy('mst_mata_kuliah_semester.tahun', 'mst_mata_kuliah_semester.semester')
                                                            ->orderBy('mst_mata_kuliah_semester.tahun')
                                                            ->orderBy('mst_mata_kuliah_semester.semester')
                                                            ->get();

        // Get curriculum distribution for active makul (yang sudah ada nilai di trx_nilai)
        $curriculumDistributionAktifQuery = DB::table('mst_mata_kuliah_kurikulum')
            ->select('mst_mata_kuliah_semester.tahun', 'mst_mata_kuliah_semester.semester as semester_num', DB::raw('COUNT(DISTINCT mst_mata_kuliah_semester.id) as count'))
            ->join('mst_mata_kuliah_semester', 'mst_mata_kuliah_kurikulum.id', '=', 'mst_mata_kuliah_semester.mkk_id')
            ->join('trx_nilai', 'mst_mata_kuliah_semester.id', '=', 'trx_nilai.mks_id');

        if ($semester !== 'all' && strpos($semester, '-') !== false) {
            list($tahun, $semesterNum) = explode('-', $semester);
            $curriculumDistributionAktifQuery->where('mst_mata_kuliah_semester.tahun', $tahun)
                                       ->where('mst_mata_kuliah_semester.semester', $semesterNum);
        }
        if ($prodiId || $kurikulum !== 'all') {
            $curriculumDistributionAktifQuery->leftJoin('mst_kurikulum', 'mst_mata_kuliah_kurikulum.kurikulum_id', '=', 'mst_kurikulum.id');
            if ($prodiId) $curriculumDistributionAktifQuery->where('mst_kurikulum.prodi_id', $prodiId);
            if ($kurikulum !== 'all') $curriculumDistributionAktifQuery->where('mst_kurikulum.id', $kurikulum);
        }

        $curriculumDistributionAktifRaw = $curriculumDistributionAktifQuery->groupBy('mst_mata_kuliah_semester.tahun', 'mst_mata_kuliah_semester.semester')
                                                            ->orderBy('mst_mata_kuliah_semester.tahun')
                                                            ->orderBy('mst_mata_kuliah_semester.semester')
                                                            ->get();

        // Gabungkan data ke bentuk array dengan key semester
        $curriculumDistribution = collect();
        foreach ($curriculumDistributionRaw as $item) {
            $key = $item->tahun . '-' . $item->semester_num;
            $curriculumDistribution->put($key, [
                'semester' => $key,
                'total' => (int)$item->count,
                'aktif' => 0
            ]);
        }
        foreach ($curriculumDistributionAktifRaw as $item) {
            $key = $item->tahun . '-' . $item->semester_num;
            if (!$curriculumDistribution->has($key)) {
                $curriculumDistribution->put($key, [
                    'semester' => $key,
                    'total' => 0,
                    'aktif' => (int)$item->count
                ]);
            } else {
                $existing = $curriculumDistribution->get($key);
                $existing['aktif'] = (int)$item->count;
                $curriculumDistribution->put($key, $existing);
            }
        }
        $curriculumDistribution = $curriculumDistribution->values();

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

    // Get CPL per Kurikulum (CPMK distribution per CPL per Kurikulum)

    $cpmkCplPerKurikulum = DB::table('mst_cpl')
        ->select('mst_kurikulum.nama as kurikulum', 'mst_cpl.nomor as cpl', DB::raw('COUNT(DISTINCT trx_cpmk_cpl.cpmk_id) as count'))
        ->leftJoin('trx_cpmk_cpl', 'mst_cpl.id', '=', 'trx_cpmk_cpl.cpl_id')
        ->leftJoin('mst_cpmk', 'trx_cpmk_cpl.cpmk_id', '=', 'mst_cpmk.id')
        ->leftJoin('mst_mata_kuliah_semester', 'mst_cpmk.mks_id', '=', 'mst_mata_kuliah_semester.id')
        ->leftJoin('mst_kurikulum', 'mst_cpl.kurikulum_id', '=', 'mst_kurikulum.id');

    if ($semester !== 'all' && strpos($semester, '-') !== false) {
        list($tahun, $semesterNum) = explode('-', $semester);
        $cpmkCplPerKurikulum->where('mst_cpmk.id', '!=', null)
            ->where('mst_mata_kuliah_semester.tahun', $tahun)
            ->where('mst_mata_kuliah_semester.semester', $semesterNum);
    }
    if ($prodiId) {
        $cpmkCplPerKurikulum->where('mst_kurikulum.prodi_id', $prodiId);
    }

    $cpmkCplPerKurikulum = $cpmkCplPerKurikulum
        ->groupBy('mst_kurikulum.nama', 'mst_cpl.nomor')
        ->orderBy('mst_kurikulum.nama')
        ->orderBy('mst_cpl.nomor')
        ->get();

    return response()->json([
        'kurikulum_options' => $kurikulumOptions,
            'tahun_aktif' => $tahunAktif,
            'semester_aktif' => $semesterAktif,
            'semester_options' => $semesterOptions,
            'cpmk_count' => $cpmkCount,
            'cpl_count' => $cplCount,
            'mks_count' => $mksCount,
            'mks_aktif_count' => $mksAktifCount,
            'cpmk_cpl_distribution' => $cpmkCplDistribution,
            'curriculum_distribution' => $curriculumDistribution,
            'mks_list' => $mksList,
            'cpmk_cpl_per_kurikulum' => $cpmkCplPerKurikulum
        ]);
    }
}
