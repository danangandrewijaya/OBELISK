<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\NilaiCpmk;
use Illuminate\Http\Request;

class NilaiController extends Controller
{    /**
     * Get CPMK values for a specific nilai record
     */
    public function getCpmkValues(Request $request, $id)
    {
        $nilai = Nilai::findOrFail($id);

        // Get nilai CPMK with CPMK relation
        $nilaiCpmk = NilaiCpmk::with('cpmk')
            ->where('nilai_id', $id)
            ->get()
            ->map(function($item) {
                return [
                    'cpmk' => [
                        'id' => $item->cpmk->id,
                        'nomor' => $item->cpmk->nomor,
                        'deskripsi' => $item->cpmk->deskripsi,
                    ],
                    'nilai' => $item->nilai_angka,
                    'bobot' => $item->nilai_bobot,
                ];
            });

        return response()->json($nilaiCpmk);
    }

    /**
     * Get a specific nilai record details
     */
    public function getNilai($id)
    {
        $nilai = Nilai::findOrFail($id);

        return response()->json([
            'id' => $nilai->id,
            'outcome' => $nilai->outcome,
            'keterangan' => $nilai->keterangan
        ]);
    }

    /**
     * Update keterangan for a specific nilai record
     */
    public function updateKeterangan(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'required|string'
        ]);

        $nilai = Nilai::findOrFail($id);
        $nilai->keterangan = $request->keterangan;
        $nilai->save();

        return response()->json([
            'success' => true,
            'message' => 'Keterangan berhasil diperbarui',
            'keterangan' => $nilai->keterangan
        ]);
    }
}
