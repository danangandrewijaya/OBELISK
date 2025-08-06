<?php

namespace App\Http\Controllers;

use App\Models\Kurikulum;
use App\Models\MataKuliahSemester;
use App\Models\NilaiPi;
use App\Models\Pi;
use App\Models\Siklus2;
use App\Models\SiklusPi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Siklus2Controller extends Controller
{
    /**
     * Display a listing of the siklus
     */
    public function index()
    {
        $siklus = Siklus2::with('kurikulum')->get();
        return view('siklus2.index', compact('siklus'));
    }

    /**
     * Show the form for creating a new siklus
     */
    public function create()
    {
        $kurikulums = Kurikulum::all();
        return view('siklus2.create', compact('kurikulums'));
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

        $siklus = Siklus2::create($request->all());

        return redirect()->route('siklus2.configure', $siklus)
            ->with('success', 'Siklus berhasil dibuat, silakan konfigurasi CPL dan mata kuliah.');
    }

    /**
     * Display the specified siklus
     */
    public function show(Siklus2 $siklus)
    {
        $cplData = $this->calculatePiData($siklus);
        return view('siklus2.show', compact('siklus', 'cplData'));
    }

    /**
     * Show the form for editing the specified siklus
     */
    public function edit(Siklus2 $siklus)
    {
        $kurikulums = Kurikulum::all();
        return view('siklus2.edit', compact('siklus', 'kurikulums'));
    }

    /**
     * Update the specified siklus
     */
    public function update(Request $request, Siklus2 $siklus)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kurikulum_id' => 'required|exists:mst_kurikulum,id',
            'tahun_mulai' => 'required|digits:4|integer|min:2000|max:' . (date('Y') + 5),
            'tahun_selesai' => 'required|digits:4|integer|min:' . $request->tahun_mulai . '|max:' . (date('Y') + 10),
        ]);

        $siklus->update($request->all());

        return redirect()->route('siklus2.index')
            ->with('success', 'Siklus berhasil diperbarui.');
    }

    /**
     * Remove the specified siklus
     */
    public function destroy(Siklus2 $siklus)
    {
        // SiklusCpl records will be automatically deleted through cascading
        $siklus->delete();

        return redirect()->route('siklus2.index')
            ->with('success', 'Siklus berhasil dihapus.');
    }

    /**
     * Show the configure page for setting up CPL and mata kuliah
     */
    public function configure(Siklus2 $siklus)
    {
        $cpls = Pi::whereHas('cpl', function($query) use ($siklus) {
            $query->where('kurikulum_id', $siklus->kurikulum_id);
        })
        ->with('cpl')
        ->join('mst_cpl', 'mst_pi.cpl_id', '=', 'mst_cpl.id')
        ->orderBy('mst_cpl.nomor')
        ->orderBy('mst_pi.nomor')
        ->select('mst_pi.*')
        ->get();
        // Get available mata kuliah semester that have CPL connections within the siklus date range
        $availableMkss = MataKuliahSemester::with('mkk')
            ->where('tahun', '>=', $siklus->tahun_mulai)
            ->where('tahun', '<=', $siklus->tahun_selesai)
            ->whereHas('mkk', function($query) use ($siklus) {
                $query->where('kurikulum_id', $siklus->kurikulum_id);
            })
            ->whereExists(function($query) {
                $query->select(DB::raw(1))
                    ->from('trx_cpmk_pi')
                    ->join('mst_cpmk', 'trx_cpmk_pi.cpmk_id', '=', 'mst_cpmk.id')
                    ->whereRaw('mst_cpmk.mks_id = mst_mata_kuliah_semester.id');
            })
            ->orderBy('mkk_id') // First sort by MKK (mata kuliah kurikulum)
            ->orderBy('tahun')  // Then by year
            ->orderBy('semester') // Then by semester
            ->get();
        // Get current selections
        $selections = [];
        foreach($cpls as $pi) {
            $selections[$pi->id] = SiklusPi::where('siklus2_id', $siklus->id)
                ->where('pi_id', $pi->id)
                ->pluck('mata_kuliah_semester_id')
                ->toArray();
        }

        // Get which MKSs are connected to which CPLs
        $mksCplConnections = [];
        foreach($availableMkss as $mks) {
            $connectedPiIds = DB::table('trx_cpmk_pi')
                ->join('mst_cpmk', 'trx_cpmk_pi.cpmk_id', '=', 'mst_cpmk.id')
                ->where('mst_cpmk.mks_id', $mks->id)
                ->join('mst_pi', 'trx_cpmk_pi.pi_id', '=', 'mst_pi.id')
                ->distinct()
                ->pluck('mst_pi.id')
                ->toArray();

            $mksCplConnections[$mks->id] = $connectedPiIds;
        }

        return view('siklus2.configure', compact('siklus', 'cpls', 'availableMkss', 'selections', 'mksCplConnections'));
    }

    /**
     * Save CPL and mata kuliah selections for the siklus
     */
    public function savePiSelections(Request $request, Siklus2 $siklus)
    {
        $request->validate([
            'cpl_selections' => 'nullable|array',
            'cpl_selections.*' => 'array',
            'cpl_selections.*.*' => 'exists:mst_mata_kuliah_semester,id',
        ]);

        // Start transaction
        DB::beginTransaction();

        try {
            // Delete existing selections
            SiklusPi::where('siklus2_id', $siklus->id)->delete();
            // Save new selections if any
            if ($request->has('cpl_selections') && is_array($request->cpl_selections)) {
                foreach($request->cpl_selections as $piId => $mksIds) {
                    foreach($mksIds as $mksId) {
                        SiklusPi::create([
                            'siklus2_id' => $siklus->id,
                            'pi_id' => $piId,
                            'mata_kuliah_semester_id' => $mksId,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('siklus2.show', $siklus)
                ->with('success', 'Konfigurasi CPL dan mata kuliah berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Calculate CPL data for the siklus
     */
    private function calculatePiData(Siklus2 $siklus)
    {
        $cplData = [];
        $pis = $siklus->pis();

        foreach($pis as $pi) {
            $mataKuliahSemesters = $siklus->getMataKuliahSemestersByPi($pi->id);

            $totalNilai = 0;
            $totalMks = 0;
            $detailNilai = [];

            foreach($mataKuliahSemesters as $mks) {
                // Process each mata kuliah semester directly
                // Get the associated matakuliah kurikulum for display purposes
                $mkk = $mks->mkk;
                // Calculate average CPL value for this MKS
                $nilaiCpls = NilaiPi::whereHas('nilai', function($query) use ($mks) {
                    $query->where('mks_id', $mks->id);
                })->where('pi_id', $pi->id)->get();

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

            $cplData[$pi->id] = [
                'cpl' => $pi,
                'rata_rata' => $totalMks > 0 ? round($totalNilai / $totalMks, 2) : 0,
                'total_mk' => $totalMks,
                'detail' => $detailNilai,
            ];
        }

        return $cplData;
    }
}
