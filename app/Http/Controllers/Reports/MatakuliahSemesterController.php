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
        $years = MataKuliahSemester::distinct()->pluck('tahun')->sort()->values();
        $semesters = MataKuliahSemester::distinct()->pluck('semester')->sort()->values();

        return $dataTable->render('report.matakuliah-semester.list', compact('years', 'semesters'));
    }

    public function show(MataKuliahSemester $matakuliahSemester)
    {
        return view('report.matakuliah-semester.show', compact('matakuliahSemester'));
    }
}
