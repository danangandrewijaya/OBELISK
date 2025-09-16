<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MataKuliahKurikulum;
use App\Models\Kurikulum;

class MataKuliahKurikulumController extends Controller
{
    public function index(Request $request)
    {
        $prodiId = session('prodi_id');
        $kurikulumId = $request->get('kurikulum_id');
        $search = $request->get('search');
        $perPage = $request->get('per_page', 25);
        $kurikulums = Kurikulum::query()->from('mst_kurikulum')->where('prodi_id', $prodiId)->get();
        $query = MataKuliahKurikulum::query();
        if ($kurikulumId) {
            $query->where('kurikulum_id', $kurikulumId);
        }
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('kode', 'like', "%$search%")
                  ->orWhere('nama', 'like', "%$search%")
                  ->orWhere('kategori', 'like', "%$search%")
                  ->orWhere('semester', 'like', "%$search%")
                  ->orWhere('sks', 'like', "%$search%") ;
            });
        }
        $query->whereHas('kurikulum', function($q) use ($prodiId) {
            $q->from('mst_kurikulum')->where('prodi_id', $prodiId);
        });
        $makulKurikulums = $query->orderBy('semester')->orderBy('kode')->paginate($perPage)->withQueryString();
        return view('master.makul_kurikulum.index', compact('makulKurikulums', 'kurikulums', 'kurikulumId', 'search'));
    }

    public function create(Request $request)
    {
    $prodiId = session('prodi_id');
    $kurikulums = Kurikulum::query()->from('mst_kurikulum')->where('prodi_id', $prodiId)->get();
    $kurikulumId = $request->get('kurikulum_id');
    return view('master.makul_kurikulum.create', compact('kurikulums', 'kurikulumId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required',
            'nama' => 'required',
            'kategori' => 'required',
            'semester' => 'required',
            'kurikulum_id' => 'required|exists:mst_kurikulum,id',
            'sks' => 'required|numeric',
        ]);
        MataKuliahKurikulum::create($request->only(['kode', 'nama', 'kategori', 'semester', 'kurikulum_id', 'sks']));
        return redirect()->route('master.matakuliah-kurikulum.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit(MataKuliahKurikulum $makulKurikulum)
    {
    $prodiId = session('prodi_id');
    $kurikulums = Kurikulum::query()->from('mst_kurikulum')->where('prodi_id', $prodiId)->get();
    return view('master.makul_kurikulum.edit', compact('makulKurikulum', 'kurikulums'));
    }

    public function update(Request $request, MataKuliahKurikulum $makulKurikulum)
    {
        $request->validate([
            'kode' => 'required',
            'nama' => 'required',
            'kategori' => 'required',
            'semester' => 'required',
            'kurikulum_id' => 'required|exists:mst_kurikulum,id',
            'sks' => 'required|numeric',
        ]);
        $makulKurikulum->update($request->only(['kode', 'nama', 'kategori', 'semester', 'kurikulum_id', 'sks']));
        return redirect()->route('master.matakuliah-kurikulum.index')->with('success', 'Data berhasil diupdate');
    }

    public function destroy(MataKuliahKurikulum $makulKurikulum)
    {
        $makulKurikulum->delete();
    return redirect()->route('master.matakuliah-kurikulum.index')->with('success', 'Data berhasil dihapus');
    }
}
