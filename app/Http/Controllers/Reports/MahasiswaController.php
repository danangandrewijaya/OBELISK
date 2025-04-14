<?php

namespace App\Http\Controllers\Reports;

use App\DataTables\MahasiswaDataTable;
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Cpl;

class MahasiswaController extends Controller
{
    public function index(MahasiswaDataTable $dataTable)
    {
        return $dataTable->render('report.mahasiswa.list');
    }

    public function show(Mahasiswa $mahasiswa)
    {
        $cpls = Cpl::
        // where('prodi_id', $mahasiswa->prodi_id)
            // ->
            orderBy('nomor')
            ->get();

        return view('report.mahasiswa.show', compact('mahasiswa', 'cpls'));
    }
}
