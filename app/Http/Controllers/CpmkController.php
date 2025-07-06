<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cpmk;

class CpmkController extends Controller
{
    public function updateTindakLanjut(Request $request, $id)
    {
        $request->validate([
            'pelaksanaan' => 'nullable|string',
            'evaluasi' => 'nullable|string',
        ]);

        $cpmk = Cpmk::findOrFail($id);
        $cpmk->pelaksanaan = $request->pelaksanaan;
        $cpmk->evaluasi = $request->evaluasi;
        $cpmk->save();

        return response()->json([
            'success' => true,
            'message' => 'Tindak lanjut CPMK berhasil diperbarui.',
            'data' => $cpmk
        ]);
    }

    public function mahasiswaBobotKurang($id)
    {
        $cpmkId = $id;
        $mahasiswa = [];

        // Ambil semua nilai mahasiswa yang terkait CPMK ini dan bobot < 1.0
        $nilaiCpmk = \App\Models\NilaiCpmk::with(['nilai.mahasiswa'])
            ->where('cpmk_id', $cpmkId)
            ->where('nilai_bobot', '<', 1.0)
            ->get();
        // dd($nilaiCpmk);

        foreach ($nilaiCpmk as $nc) {
            if ($nc->nilai && $nc->nilai->mahasiswa) {
                $mahasiswa[] = [
                    'nim' => $nc->nilai->mahasiswa->nim,
                    'nama' => $nc->nilai->mahasiswa->nama,
                    'bobot' => $nc->nilai_bobot,
                ];
            }
        }

        return response()->json($mahasiswa);
    }
}
