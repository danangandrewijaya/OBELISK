<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Cpl;
use App\Models\Kurikulum;
use Illuminate\Http\Request;

class CplController extends Controller
{
    public function index(Request $request)
    {
        $kurikulumId = $request->get('kurikulum_id');
        $kurikulums = Kurikulum::where('prodi_id', session('prodi_id'))->get();
        $query = Cpl::query()->whereHas('kurikulum', function($q) { $q->where('prodi_id', session('prodi_id')); });
        if ($kurikulumId) {
            $query->where('kurikulum_id', $kurikulumId);
        }
        $cpls = $query->get();
        return view('master.cpl.index', compact('cpls', 'kurikulums', 'kurikulumId'));
    }

    public function create(Request $request)
    {
        $kurikulums = Kurikulum::where('prodi_id', session('prodi_id'))->get();
        $selectedKurikulum = $request->get('kurikulum_id');
        return view('master.cpl.create', compact('kurikulums', 'selectedKurikulum'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor' => 'required|integer',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kurikulum_id' => 'required|exists:mst_kurikulum,id',
        ]);
        Cpl::create($validated);
        return redirect()->route('master.cpl.index', ['kurikulum_id' => $validated['kurikulum_id']])->with('success', 'CPL berhasil ditambahkan.');
    }

    public function edit(Cpl $cpl)
    {
        $kurikulums = Kurikulum::where('prodi_id', session('prodi_id'))->get();
        return view('master.cpl.edit', compact('cpl', 'kurikulums'));
    }

    public function update(Request $request, Cpl $cpl)
    {
        $validated = $request->validate([
            'nomor' => 'required|integer',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kurikulum_id' => 'required|exists:mst_kurikulum,id',
        ]);
        $cpl->update($validated);
        return redirect()->route('master.cpl.index', ['kurikulum_id' => $validated['kurikulum_id']])->with('success', 'CPL berhasil diupdate.');
    }

    public function destroy(Cpl $cpl)
    {
        $kurikulumId = $cpl->kurikulum_id;
        $cpl->delete();
        return redirect()->route('master.cpl.index', ['kurikulum_id' => $kurikulumId])->with('success', 'CPL berhasil dihapus.');
    }
}
