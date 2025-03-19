<?php

namespace App\Http\Controllers\Reports;

use App\DataTables\MahasiswaDataTable;
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;

class MahasiswaController extends Controller
{
    public function index(MahasiswaDataTable $dataTable)
    {
        return $dataTable->render('report.list');
    }

    public function show(Mahasiswa $mahasiswa)
    {
        return view('report.mahasiswa.show', compact('mahasiswa'));
    }
}
