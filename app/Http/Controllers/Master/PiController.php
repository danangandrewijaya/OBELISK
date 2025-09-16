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
        $prodiId = session('prodi_id');
        $kurikulums = Kurikulum::where('prodi_id', $prodiId)->get();
        $cpls = collect();
        $query = Pi::query();
        // Selalu filter PI berdasarkan prodi user
        if ($prodiId) {
            $query->whereHas('cpl.kurikulum', function($q) use ($prodiId) {
                $q->where('prodi_id', $prodiId);
            });
        }
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
            'cpl_id' => [
                'required',
                'exists:mst_cpl,id',
                function ($attribute, $value, $fail) {
                    $prodiId = session('prodi_id');
                    if ($prodiId) {
                        $cpl = \App\Models\Cpl::find($value);
                        if (!$cpl || $cpl->prodi_id != $prodiId) {
                            $fail('CPL tidak valid untuk prodi Anda.');
                        }
                    }
                }
            ],
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
        // Pastikan PI yang diupdate milik prodi user
        $prodiId = session('prodi_id');
        if ($prodiId && (!$pi->cpl || $pi->cpl->prodi_id != $prodiId)) {
            abort(403, 'PI tidak valid untuk prodi Anda.');
        }
        $validated = $request->validate([
            'nomor' => 'required|integer|min:1',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'cpl_id' => [
                'required',
                'exists:mst_cpl,id',
                function ($attribute, $value, $fail) use ($prodiId) {
                    if ($prodiId) {
                        $cpl = \App\Models\Cpl::find($value);
                        if (!$cpl || $cpl->prodi_id != $prodiId) {
                            $fail('CPL tidak valid untuk prodi Anda.');
                        }
                    }
                }
            ],
        ]);
        $pi->update($validated);
        return redirect()->route('master.pi.index', ['cpl_id' => $validated['cpl_id']])->with('success', 'PI berhasil diupdate.');
    }

    public function destroy(Pi $pi)
    {
        $prodiId = session('prodi_id');
        if ($prodiId && (!$pi->cpl || $pi->cpl->prodi_id != $prodiId)) {
            abort(403, 'PI tidak valid untuk prodi Anda.');
        }
        $cplId = $pi->cpl_id;
        $pi->delete();
        return redirect()->route('master.pi.index', ['cpl_id' => $cplId])->with('success', 'PI berhasil dihapus.');
    }
}
