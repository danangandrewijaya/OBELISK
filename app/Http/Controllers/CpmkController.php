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
}
