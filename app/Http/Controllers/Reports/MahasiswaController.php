<?php

namespace App\Http\Controllers\Reports;

use App\DataTables\MahasiswaDataTable;
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Cpl;

use App\Models\Kurikulum;
use Illuminate\Http\Request;

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

    $prodiId = session('prodi_id');
    $kurikulumsQuery = Kurikulum::query();
    if ($prodiId) $kurikulumsQuery->where('prodi_id', $prodiId);
    $kurikulums = $kurikulumsQuery->get();

        // Nilai untuk transkrip lengkap (tanpa filter kurikulum)
        $nilaiTranskrip = $mahasiswa->nilai()->with('mks.mkk')->get();

        // Nilai yang sudah difilter berdasarkan kurikulum mahasiswa
        $nilaiFiltered = $mahasiswa->nilai()
            ->whereHas('mks.mkk', function ($query) use ($mahasiswa) {
                $query->where('kurikulum_id', $mahasiswa->kurikulum_id);
            })
            ->with(['mks.mkk', 'nilaiCpmk.cpmk.cpmkCpl'])
            ->get();

        return view('report.mahasiswa.show', compact('mahasiswa', 'cpls', 'kurikulums', 'nilaiTranskrip', 'nilaiFiltered'));
    }

    public function updateKurikulum(Request $request, Mahasiswa $mahasiswa)
    {
        $request->validate([
            'kurikulum_id' => 'required|exists:'.(new Kurikulum())->getTable().',id',
        ]);

        $mahasiswa->update([
            'kurikulum_id' => $request->kurikulum_id,
        ]);

        return redirect()->back()->with('success', 'Kurikulum mahasiswa berhasil diperbarui.');
    }
}
