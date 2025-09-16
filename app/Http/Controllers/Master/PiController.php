<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Pi;
use App\Models\Cpl;
use App\Models\Kurikulum;
use Illuminate\Http\Request;

class PiController extends Controller
{
    public function index(Request $request)
    {
        $kurikulumId = $request->get('kurikulum_id');
        $cplId = $request->get('cpl_id');
        $kurikulums = Kurikulum::where('prodi_id', session('prodi_id'))->get();
        $cpls = collect();
        $query = Pi::query();
        if ($kurikulumId) {
            $cpls = Cpl::where('kurikulum_id', $kurikulumId)->get();
            $query->whereHas('cpl', function($q) use ($kurikulumId) {
                $q->where('kurikulum_id', $kurikulumId);
            });
        }
        if ($cplId) {
            $query->where('cpl_id', $cplId);
        }
        $pis = $query->get();
        return view('master.pi.index', compact('pis', 'kurikulums', 'cpls', 'kurikulumId', 'cplId'));
    }

    public function create(Request $request)
    {
        $kurikulums = Kurikulum::where('prodi_id', session('prodi_id'))->get();
        $selectedKurikulum = $request->get('kurikulum_id');
        $cpls = $selectedKurikulum ? Cpl::where('kurikulum_id', $selectedKurikulum)->get() : collect();
        $selectedCpl = $request->get('cpl_id');
        return view('master.pi.create', compact('kurikulums', 'cpls', 'selectedKurikulum', 'selectedCpl'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor' => 'required|integer|min:1',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'cpl_id' => 'required|exists:mst_cpl,id',
        ]);
        Pi::create($validated);
        return redirect()->route('master.pi.index', ['cpl_id' => $validated['cpl_id']])->with('success', 'PI berhasil ditambahkan.');
    }

    public function edit(Pi $pi)
    {
        $kurikulums = Kurikulum::where('prodi_id', session('prodi_id'))->get();
        $cpls = $pi->cpl ? Cpl::where('kurikulum_id', $pi->cpl->kurikulum_id)->get() : collect();
        return view('master.pi.edit', compact('pi', 'kurikulums', 'cpls'));
    }

    public function update(Request $request, Pi $pi)
    {
        $validated = $request->validate([
            'nomor' => 'required|integer|min:1',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'cpl_id' => 'required|exists:mst_cpl,id',
        ]);
        $pi->update($validated);
        return redirect()->route('master.pi.index', ['cpl_id' => $validated['cpl_id']])->with('success', 'PI berhasil diupdate.');
    }

    public function destroy(Pi $pi)
    {
        $cplId = $pi->cpl_id;
        $pi->delete();
        return redirect()->route('master.pi.index', ['cpl_id' => $cplId])->with('success', 'PI berhasil dihapus.');
    }
}
