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
        $angkatanList = Mahasiswa::select('angkatan')
            ->distinct()
            ->orderBy('angkatan', 'desc')
            ->pluck('angkatan');

        return $dataTable->render('report.mahasiswa.list', compact('angkatanList'));
    }

    public function show(Mahasiswa $mahasiswa)
    {
        $cpls = Cpl::where('kurikulum_id', $mahasiswa->kurikulum_id)
            ->orderBy('nomor')
            ->get();

        return view('report.mahasiswa.show', compact('mahasiswa', 'cpls'));
    }
}
