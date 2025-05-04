<?php

namespace App\Http\Controllers;

use App\Models\Cpl;
use App\Models\Kurikulum;
use App\Models\MataKuliahKurikulum;
use App\Models\MataKuliahSemester;
use App\Models\NilaiCpl;
use App\Models\Siklus;
use App\Models\SiklusCpl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiklusController extends Controller
{
    /**
     * Display a listing of the siklus
     */
    public function index()
    {
        $siklus = Siklus::with('kurikulum')->get();
        return view('siklus.index', compact('siklus'));
    }

    /**
     * Show the form for creating a new siklus
     */
    public function create()
    {
        $kurikulums = Kurikulum::all();
        return view('siklus.create', compact('kurikulums'));
    }

    /**
     * Store a newly created siklus
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kurikulum_id' => 'required|exists:mst_kurikulum,id',
            'tahun_mulai' => 'required|digits:4|integer|min:2000|max:' . (date('Y') + 5),
            'tahun_selesai' => 'required|digits:4|integer|min:' . $request->tahun_mulai . '|max:' . (date('Y') + 10),
        ]);

        $siklus = Siklus::create($request->all());

        return redirect()->route('siklus.configure', $siklus)
            ->with('success', 'Siklus berhasil dibuat, silakan konfigurasi CPL dan mata kuliah.');
    }

    /**
     * Display the specified siklus
     */
    public function show(Siklus $siklus)
    {
        $cplData = $this->calculateCplData($siklus);
        return view('siklus.show', compact('siklus', 'cplData'));
    }

    /**
     * Show the form for editing the specified siklus
     */
    public function edit(Siklus $siklus)
    {
        $kurikulums = Kurikulum::all();
        return view('siklus.edit', compact('siklus', 'kurikulums'));
    }

    /**
     * Update the specified siklus
     */
    public function update(Request $request, Siklus $siklus)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kurikulum_id' => 'required|exists:mst_kurikulum,id',
            'tahun_mulai' => 'required|digits:4|integer|min:2000|max:' . (date('Y') + 5),
            'tahun_selesai' => 'required|digits:4|integer|min:' . $request->tahun_mulai . '|max:' . (date('Y') + 10),
        ]);

        $siklus->update($request->all());

        return redirect()->route('siklus.index')
            ->with('success', 'Siklus berhasil diperbarui.');
    }

    /**
     * Remove the specified siklus
     */
    public function destroy(Siklus $siklus)
    {
        // SiklusCpl records will be automatically deleted through cascading
        $siklus->delete();

        return redirect()->route('siklus.index')
            ->with('success', 'Siklus berhasil dihapus.');
    }

    /**
     * Show the configure page for setting up CPL and mata kuliah
     */
    public function configure(Siklus $siklus)
    {
        $cpls = Cpl::where('kurikulum_id', $siklus->kurikulum_id)->orderBy('nomor')->get();

        // Get available mata kuliah kurikulum that have CPL connections
        $availableMkks = MataKuliahKurikulum::where('kurikulum_id', $siklus->kurikulum_id)
            ->whereExists(function($query) {
                $query->select(DB::raw(1))
                    ->from('trx_cpmk_cpl')
                    ->join('mst_cpmk', 'trx_cpmk_cpl.cpmk_id', '=', 'mst_cpmk.id')
                    ->join('mst_mata_kuliah_semester', 'mst_cpmk.mks_id', '=', 'mst_mata_kuliah_semester.id')
                    ->whereRaw('mst_mata_kuliah_semester.mkk_id = mst_mata_kuliah_kurikulum.id');
            })
            ->orderBy('kode')
            ->get();

        // Get current selections
        $selections = [];
        foreach($cpls as $cpl) {
            $selections[$cpl->id] = SiklusCpl::where('siklus_id', $siklus->id)
                ->where('cpl_id', $cpl->id)
                ->pluck('mata_kuliah_kurikulum_id')
                ->toArray();
        }

        // Get which MKKs are connected to which CPLs
        $mkkCplConnections = [];
        foreach($availableMkks as $mkk) {
            $connectedCplIds = DB::table('trx_cpmk_cpl')
                ->join('mst_cpmk', 'trx_cpmk_cpl.cpmk_id', '=', 'mst_cpmk.id')
                ->join('mst_mata_kuliah_semester', 'mst_cpmk.mks_id', '=', 'mst_mata_kuliah_semester.id')
                ->where('mst_mata_kuliah_semester.mkk_id', $mkk->id)
                ->join('mst_cpl', 'trx_cpmk_cpl.cpl_id', '=', 'mst_cpl.id')
                ->distinct()
                ->pluck('mst_cpl.id')
                ->toArray();

            $mkkCplConnections[$mkk->id] = $connectedCplIds;
        }

        return view('siklus.configure', compact('siklus', 'cpls', 'availableMkks', 'selections', 'mkkCplConnections'));
    }

    /**
     * Save CPL and mata kuliah selections for the siklus
     */
    public function saveCplSelections(Request $request, Siklus $siklus)
    {
        $request->validate([
            'cpl_selections' => 'required|array',
            'cpl_selections.*' => 'array',
            'cpl_selections.*.*' => 'exists:mst_mata_kuliah_kurikulum,id',
        ]);

        // Start transaction
        DB::beginTransaction();

        try {
            // Delete existing selections
            SiklusCpl::where('siklus_id', $siklus->id)->delete();

            // Save new selections
            foreach($request->cpl_selections as $cplId => $mkkIds) {
                foreach($mkkIds as $mkkId) {
                    SiklusCpl::create([
                        'siklus_id' => $siklus->id,
                        'cpl_id' => $cplId,
                        'mata_kuliah_kurikulum_id' => $mkkId,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('siklus.show', $siklus)
                ->with('success', 'Konfigurasi CPL dan mata kuliah berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Calculate CPL data for the siklus
     */
    private function calculateCplData(Siklus $siklus)
    {
        $cplData = [];
        $cpls = $siklus->cpls();

        foreach($cpls as $cpl) {
            $mataKuliahs = $siklus->getMataKuliahKurikulumsByCpl($cpl->id);
            $totalNilai = 0;
            $totalMks = 0;
            $detailNilai = [];

            foreach($mataKuliahs as $mkk) {
                // Get mata kuliah semesters in the siklus date range
                $mksList = MataKuliahSemester::where('mkk_id', $mkk->id)
                    ->where('tahun', '>=', $siklus->tahun_mulai)
                    ->where('tahun', '<=', $siklus->tahun_selesai)
                    ->get();

                foreach($mksList as $mks) {
                    // Calculate average CPL value for this MKS
                    $nilaiCpls = NilaiCpl::whereHas('nilai', function($query) use ($mks) {
                        $query->where('mks_id', $mks->id);
                    })->where('cpl_id', $cpl->id)->get();

                    if($nilaiCpls->count() > 0) {
                        $avgNilai = $nilaiCpls->avg('nilai_angka');
                        $totalNilai += $avgNilai;
                        $totalMks++;

                        $detailNilai[] = [
                            'kode' => $mkk->kode,
                            'nama' => $mkk->nama,
                            'tahun' => $mks->tahun,
                            'semester' => $mks->semester == 1 ? 'Ganjil' : 'Genap',
                            'nilai' => round($avgNilai, 2),
                        ];
                    }
                }
            }

            $cplData[$cpl->id] = [
                'cpl' => $cpl,
                'rata_rata' => $totalMks > 0 ? round($totalNilai / $totalMks, 2) : 0,
                'total_mk' => $totalMks,
                'detail' => $detailNilai,
            ];
        }

        return $cplData;
    }
}
