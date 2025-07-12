<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function destroy(Nilai $nilai)
    {
        $nilai->delete();

        return redirect()->back()->with('success', 'Nilai berhasil dihapus.');
    }
}
