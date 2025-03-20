<?php

namespace App\Http\Controllers;

use App\Models\Cpmk;
use App\Models\Cpl;
use App\Models\MataKuliahSemester;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        addVendors(['amcharts', 'amcharts-maps', 'amcharts-stock']);
        return view('pages/dashboards.index');
    }

    public function getStats(): JsonResponse
    {
        $tahunAktif = 2024;
        $semesterAktif = 1;

        // Get active MKS count
        $mksCount = MataKuliahSemester::where('tahun', $tahunAktif)
            ->where('semester', $semesterAktif)
            ->count();

        // Get CPMK count for active semester
        $cpmkCount = Cpmk::whereHas('mks', function ($query) use ($tahunAktif, $semesterAktif) {
            $query->where('tahun', $tahunAktif)
                ->where('semester', $semesterAktif);
        })->count();

        // Get CPL count for active semester
        $cplCount = Cpl::whereHas('cpmks.mks', function ($query) use ($tahunAktif, $semesterAktif) {
            $query->where('tahun', $tahunAktif)
                ->where('semester', $semesterAktif);
        })->count();

        // Get CPMK distribution per CPL for active semester
        $cpmkCplDistribution = DB::table('mst_cpl')
            ->select('mst_cpl.nomor as cpl', DB::raw('COUNT(DISTINCT trx_cpmk_cpl.cpmk_id) as count'))
            ->leftJoin('trx_cpmk_cpl', 'mst_cpl.id', '=', 'trx_cpmk_cpl.cpl_id')
            ->leftJoin('mst_cpmk', 'trx_cpmk_cpl.cpmk_id', '=', 'mst_cpmk.id')
            ->leftJoin('mst_mata_kuliah_semester', 'mst_cpmk.mks_id', '=', 'mst_mata_kuliah_semester.id')
            ->where('mst_mata_kuliah_semester.tahun', $tahunAktif)
            ->where('mst_mata_kuliah_semester.semester', $semesterAktif)
            ->groupBy('mst_cpl.nomor')
            ->orderBy('mst_cpl.nomor')
            ->get();

        // Get MKS list with CPMK and CPL counts
        $mksList = MataKuliahSemester::with(['mkk'])
            ->where('tahun', $tahunAktif)
            ->where('semester', $semesterAktif)
            ->get()
            ->map(function ($mks) {
                $cpmkCount = Cpmk::where('mks_id', $mks->id)->count();
                $cplCount = DB::table('trx_cpmk_cpl')
                    ->join('mst_cpmk', 'trx_cpmk_cpl.cpmk_id', '=', 'mst_cpmk.id')
                    ->where('mst_cpmk.mks_id', $mks->id)
                    ->distinct('trx_cpmk_cpl.cpl_id')
                    ->count();

                return [
                    'id' => $mks->id,
                    'kode' => $mks->mkk->kode,
                    'nama' => $mks->mkk->nama,
                    'sks' => $mks->mkk->sks,
                    'cpmks_count' => $cpmkCount,
                    'cpls_count' => $cplCount,
                    'rps_status' => $mks->rps_status ?? 'draft'
                ];
            });

        // Get RPS status counts
        $rpsStatus = [
            'draft_count' => $mksList->where('rps_status', 'draft')->count(),
            'published_count' => $mksList->where('rps_status', 'published')->count()
        ];

        return response()->json([
            'tahun_aktif' => $tahunAktif,
            'semester_aktif' => $semesterAktif,
            'cpmk_count' => $cpmkCount,
            'cpl_count' => $cplCount,
            'mks_count' => $mksCount,
            'cpmk_cpl_distribution' => $cpmkCplDistribution,
            'mks_list' => $mksList,
            'rps_status' => $rpsStatus
        ]);
    }
}
