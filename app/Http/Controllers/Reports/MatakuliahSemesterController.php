<?php

namespace App\Http\Controllers\Reports;

use App\DataTables\MatakuliahSemesterDataTable;
use App\Http\Controllers\Controller;
use App\Models\MataKuliahSemester;
use Illuminate\Http\Request;

class MatakuliahSemesterController extends Controller
{
    public function index(Request $request, MatakuliahSemesterDataTable $dataTable)
    {
        $prodiId = session('prodi_id');

        $yearsQuery = MataKuliahSemester::query();
        $semestersQuery = MataKuliahSemester::query();
        if ($prodiId) {
            $yearsQuery->whereHas('mkk', function ($q) use ($prodiId) {
                $q->whereHas('kurikulum', function ($q2) use ($prodiId) {
                    $q2->where('prodi_id', $prodiId);
                });
            });
            $semestersQuery->whereHas('mkk', function ($q) use ($prodiId) {
                $q->whereHas('kurikulum', function ($q2) use ($prodiId) {
                    $q2->where('prodi_id', $prodiId);
                });
            });
        }

        $years = $yearsQuery->distinct()->pluck('tahun')->sort()->values();
        $semesters = $semestersQuery->distinct()->pluck('semester')->sort()->values();

        return $dataTable->render('report.matakuliah-semester.list', compact('years', 'semesters'));
    }

    public function show(MataKuliahSemester $matakuliahSemester)
    {
        $matakuliahSemester->load([
            'mkk',
            'cpmk.cpmkCpl.cpl',
            'nilaiMahasiswa.mahasiswa.prodi',
            'nilaiMahasiswa.nilaiCpmk.cpmk'
        ]);

        return view('report.matakuliah-semester.show', compact('matakuliahSemester'));
    }
}
