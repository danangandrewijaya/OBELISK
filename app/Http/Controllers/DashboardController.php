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
        $cpmkCount = Cpmk::count();
        $cplCount = Cpl::count();
        $mksCount = MataKuliahSemester::count();

        // Get CPMK distribution per CPL
        $cpmkCplDistribution = DB::table('trx_cpmk_cpl')
            ->join('mst_cpl', 'trx_cpmk_cpl.cpl_id', '=', 'mst_cpl.id')
            ->select('mst_cpl.nomor as cpl', DB::raw('count(*) as count'))
            ->groupBy('mst_cpl.nomor')
            ->orderBy('mst_cpl.nomor')
            ->get();

        // Get MKS distribution per semester
        $mksDistribution = MataKuliahSemester::join('mst_mata_kuliah_kurikulum as mkk', 'mst_mata_kuliah_semester.mkk_id', '=', 'mkk.id')
            ->select('mkk.semester', DB::raw('count(*) as count'))
            ->groupBy('mkk.semester')
            ->orderBy('mkk.semester')
            ->get();

        return response()->json([
            'cpmk_count' => $cpmkCount,
            'cpl_count' => $cplCount,
            'mks_count' => $mksCount,
            'cpmk_cpl_distribution' => $cpmkCplDistribution,
            'mks_distribution' => $mksDistribution
        ]);
    }
}
