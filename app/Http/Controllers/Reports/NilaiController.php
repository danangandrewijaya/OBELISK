<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NilaiController extends Controller
{
    public function destroy(Nilai $nilai)
    {
        // Load relationships for logging purposes before deletion
        $nilai->load('mahasiswa', 'mks.mkk');

        $user = Auth::user();

        Log::info('Nilai record deleted.', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'record_id' => $nilai->id,
            'mahasiswa_nim' => $nilai->mahasiswa->nim,
            'mahasiswa_nama' => $nilai->mahasiswa->nama,
            'matakuliah' => $nilai->mks->mkk->kode . ' - ' . $nilai->mks->mkk->nama,
            'tahun_semester' => $nilai->mks->tahun . '/' . $nilai->mks->semester,
            'nilai_akhir' => $nilai->nilai_akhir_angka
        ]);

        $nilai->delete();

        return redirect()->back()->with('success', 'Nilai berhasil dihapus.');
    }
}
