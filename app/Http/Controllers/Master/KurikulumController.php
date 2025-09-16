<?php

namespace App\Http\Controllers\Master;

use App\Models\Kurikulum;
use Illuminate\Http\Request;

class KurikulumController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        $kurikulums = Kurikulum::all();
        return view('master.kurikulum.index', compact('kurikulums'));
    }

    public function create()
    {
        return view('master.kurikulum.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
        ]);
        $validated['prodi_id'] = session('prodi_id');
        Kurikulum::create($validated);
        return redirect()->route('master.kurikulum.index')->with('success', 'Kurikulum berhasil ditambahkan.');
    }

    public function show(Kurikulum $kurikulum)
    {
        $kurikulum->load('cpls');
        return view('master.kurikulum.show', compact('kurikulum'));
    }

    public function edit(Kurikulum $kurikulum)
    {
        return view('master.kurikulum.edit', compact('kurikulum'));
    }

    public function update(Request $request, Kurikulum $kurikulum)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
        ]);
        $validated['prodi_id'] = session('prodi_id');
        $kurikulum->update($validated);
        return redirect()->route('master.kurikulum.index')->with('success', 'Kurikulum berhasil diupdate.');
    }

    public function destroy(Kurikulum $kurikulum)
    {
        $kurikulum->delete();
        return redirect()->route('master.kurikulum.index')->with('success', 'Kurikulum berhasil dihapus.');
    }
}
