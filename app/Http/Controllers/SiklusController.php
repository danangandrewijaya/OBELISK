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
        $prodiId = session('prodi_id');
        $siklusQuery = Siklus::with('kurikulum');
        if ($prodiId) {
            $siklusQuery->whereHas('kurikulum', function ($q) use ($prodiId) {
                $q->where('prodi_id', $prodiId);
            });
        }
        $siklus = $siklusQuery->get();

        return view('siklus.index', compact('siklus', 'prodiId'));
    }

    /**
     * Show the form for creating a new siklus
     */
    public function create()
    {
    $prodiId = session('prodi_id');
    $kurikulumsQuery = Kurikulum::query();
    if ($prodiId) $kurikulumsQuery->where('prodi_id', $prodiId);
    $kurikulums = $kurikulumsQuery->get();
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
    $perAngkatanData = $this->calculatePerAngkatanData($siklus);
    $cplPerAngkatanData = $this->calculateCplPerAngkatan($siklus);
    return view('siklus.show', compact('siklus', 'cplData', 'perAngkatanData', 'cplPerAngkatanData'));
    }

    /**
     * Show the form for editing the specified siklus
     */
    public function edit(Siklus $siklus)
    {
    $prodiId = session('prodi_id');
    $kurikulumsQuery = Kurikulum::query();
    if ($prodiId) $kurikulumsQuery->where('prodi_id', $prodiId);
    $kurikulums = $kurikulumsQuery->get();
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
        $cpls = Cpl::where('kurikulum_id', $siklus->kurikulum_id)->orderBy('nomor')->get();        // Get available mata kuliah semester that have CPL connections within the siklus date range
        $availableMkss = MataKuliahSemester::with('mkk')
            ->where('tahun', '>=', $siklus->tahun_mulai)
            ->where('tahun', '<=', $siklus->tahun_selesai)
            ->whereHas('mkk', function($query) use ($siklus) {
                $query->where('kurikulum_id', $siklus->kurikulum_id);
            })
            ->whereExists(function($query) {
                $query->select(DB::raw(1))
                    ->from('trx_cpmk_cpl')
                    ->join('mst_cpmk', 'trx_cpmk_cpl.cpmk_id', '=', 'mst_cpmk.id')
                    ->whereRaw('mst_cpmk.mks_id = mst_mata_kuliah_semester.id');
            })
            ->orderBy('mkk_id') // First sort by MKK (mata kuliah kurikulum)
            ->orderBy('tahun')  // Then by year
            ->orderBy('semester') // Then by semester
            ->get();        // Get current selections
        $selections = [];
        foreach($cpls as $cpl) {
            $selections[$cpl->id] = SiklusCpl::where('siklus_id', $siklus->id)
                ->where('cpl_id', $cpl->id)
                ->pluck('mata_kuliah_semester_id')
                ->toArray();
        }

        // Get which MKSs are connected to which CPLs
        $mksCplConnections = [];
        foreach($availableMkss as $mks) {
            $connectedCplIds = DB::table('trx_cpmk_cpl')
                ->join('mst_cpmk', 'trx_cpmk_cpl.cpmk_id', '=', 'mst_cpmk.id')
                ->where('mst_cpmk.mks_id', $mks->id)
                ->join('mst_cpl', 'trx_cpmk_cpl.cpl_id', '=', 'mst_cpl.id')
                ->distinct()
                ->pluck('mst_cpl.id')
                ->toArray();

            $mksCplConnections[$mks->id] = $connectedCplIds;
        }

        return view('siklus.configure', compact('siklus', 'cpls', 'availableMkss', 'selections', 'mksCplConnections'));
    }

    /**
     * Save CPL and mata kuliah selections for the siklus
     */    public function saveCplSelections(Request $request, Siklus $siklus)
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
            SiklusCpl::where('siklus_id', $siklus->id)->delete();            // Save new selections if any
            if ($request->has('cpl_selections') && is_array($request->cpl_selections)) {
                foreach($request->cpl_selections as $cplId => $mksIds) {
                    foreach($mksIds as $mksId) {
                        SiklusCpl::create([
                            'siklus_id' => $siklus->id,
                            'cpl_id' => $cplId,
                            'mata_kuliah_semester_id' => $mksId,
                        ]);
                    }
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
     */    private function calculateCplData(Siklus $siklus)
    {
        $cplData = [];
        $cpls = $siklus->cpls();

        foreach($cpls as $cpl) {
            $mataKuliahSemesters = $siklus->getMataKuliahSemestersByCpl($cpl->id);
            $totalNilai = 0;
            $totalMks = 0;
            $detailNilai = [];

            foreach($mataKuliahSemesters as $mks) {
                // Process each mata kuliah semester directly
                // Get the associated matakuliah kurikulum for display purposes
                $mkk = $mks->mkk;                // Calculate average CPL value for this MKS
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

            $rataRata100 = $totalMks > 0 ? round($totalNilai / $totalMks, 2) : 0;
            $rataRata4 = $rataRata100 / 25; // Konversi ke skala 4

            $rataRata100 = $totalMks > 0 ? round($totalNilai / $totalMks, 2) : 0;
            $rataRata4 = ($rataRata100 / 100) * 4; // Konversi ke skala 4

            $cplData[$cpl->id] = [
                'cpl' => $cpl,
                'rata_rata' => $rataRata100,
                'rata_rata_4' => $rataRata4,
                'total_mk' => $totalMks,
                'detail' => $detailNilai,
            ];
        }

        return $cplData;
    }

    /**
     * Calculate average CPL achievement per angkatan (tahun) for the siklus
     * Returns array: ['labels' => [...tahun...], 'values_100' => [...], 'values_4' => [...]]
     */
    private function calculatePerAngkatanData(Siklus $siklus)
    {
        // Get selected mata kuliah semester ids for this siklus
        $selectedMksIds = SiklusCpl::where('siklus_id', $siklus->id)
            ->pluck('mata_kuliah_semester_id')
            ->toArray();

        if (empty($selectedMksIds)) {
            return ['labels' => [], 'values_100' => [], 'values_4' => []];
        }

        // Get MKS records for those ids
        $mksList = MataKuliahSemester::whereIn('id', $selectedMksIds)
            ->orderBy('tahun')
            ->get();

        // Group MKS ids by year
        $byYear = [];
        foreach ($mksList as $mks) {
            $year = $mks->tahun;
            if (!isset($byYear[$year])) $byYear[$year] = [];
            $byYear[$year][] = $mks->id;
        }

        $labels = [];
        $values100 = [];
        $values4 = [];

        foreach ($byYear as $year => $mksIds) {
            // Average all NilaiCpl records for mks in this year
            $avg = NilaiCpl::whereHas('nilai', function($q) use ($mksIds) {
                $q->whereIn('mks_id', $mksIds);
            })->avg('nilai_angka');

            $avg = $avg !== null ? round($avg, 2) : 0;

            $labels[] = (string)$year;
            $values100[] = $avg;
            $values4[] = ($avg / 100) * 4;
        }

        return [
            'labels' => $labels,
            'values_100' => $values100,
            'values_4' => $values4,
        ];
    }

    /**
     * Calculate CPL averages per CPL grouped by angkatan (tahun) for the siklus
     * Returns array: ['cpl_labels' => [...], 'years' => [...], 'datasets' => [year => ['data' => [...], 'data_4' => [...]]]]
     */
    private function calculateCplPerAngkatan(Siklus $siklus)
    {
        $cpls = $siklus->cpls();

        // collect all years present in selected MKS for this siklus
        $selectedMksIds = SiklusCpl::where('siklus_id', $siklus->id)
            ->pluck('mata_kuliah_semester_id')
            ->toArray();

        $years = [];
        if (!empty($selectedMksIds)) {
            $mksList = MataKuliahSemester::whereIn('id', $selectedMksIds)->get();
            foreach ($mksList as $mks) {
                $years[] = $mks->tahun;
            }
        }

        $years = array_values(array_unique($years));
        sort($years);

        $cplLabels = [];
        $datasets = []; // keyed by year

        foreach ($years as $year) {
            $datasets[$year] = ['data' => [], 'data_4' => []];
        }

        foreach ($cpls as $cpl) {
            $cplLabels[] = 'CPL ' . $cpl->nomor;

            // for each year, compute average for this CPL considering only MKS selected under this CPL and that year
            foreach ($years as $year) {
                // get MKS for this CPL and year
                $mksList = $siklus->getMataKuliahSemestersByCpl($cpl->id)->filter(function($mks) use ($year) {
                    return $mks->tahun == $year;
                });

                $mksIds = $mksList->pluck('id')->toArray();

                if (empty($mksIds)) {
                    $avg = 0;
                } else {
                    $avg = NilaiCpl::where('cpl_id', $cpl->id)
                        ->whereHas('nilai', function($q) use ($mksIds) {
                            $q->whereIn('mks_id', $mksIds);
                        })->avg('nilai_angka');

                    $avg = $avg !== null ? round($avg, 2) : 0;
                }

                $datasets[$year]['data'][] = $avg;
                $datasets[$year]['data_4'][] = ($avg / 100) * 4;
            }
        }

        return [
            'cpl_labels' => $cplLabels,
            'years' => $years,
            'datasets' => $datasets,
        ];
    }

    /**
     * Show the CPL comparison page
     */
    public function compareCPL()
    {
        $prodiId = session('prodi_id');
        $siklusQuery = Siklus::orderBy('tahun_mulai', 'desc');
        if ($prodiId) {
            $siklusQuery->whereHas('kurikulum', function ($q) use ($prodiId) {
                $q->where('prodi_id', $prodiId);
            });
        }
        $siklusList = $siklusQuery->get();

        return view('siklus.compare-cpl', compact('siklusList', 'prodiId'));
    }

    /**
     * Get CPL comparison data for the chart
     */
    public function getCompareCPLData(Request $request)
    {
        $siklusIds = $request->input('siklus_ids');

        // Validate input
        if (empty($siklusIds) || !is_array($siklusIds)) {
            return response()->json(['error' => 'Parameter siklus_ids diperlukan'], 400);
        }

        // Validate maximum 4 siklus
        if (count($siklusIds) > 4) {
            return response()->json(['error' => 'Maksimal 4 siklus yang dapat dibandingkan'], 400);
        }

        // Collect all CPL 'nomor' labels across selected siklus' kurikulums
        $allNomors = [];
        $siklusObjs = [];
        $prodiId = session('prodi_id');
        foreach ($siklusIds as $siklusId) {
            $siklus = Siklus::find($siklusId);
            if (!$siklus) continue;
            if ($prodiId) {
                // ensure the siklus' kurikulum belongs to prodi
                if (!isset($siklus->kurikulum) || $siklus->kurikulum->prodi_id != $prodiId) continue;
            }
            $siklusObjs[] = $siklus;
            $cpls = Cpl::where('kurikulum_id', $siklus->kurikulum_id)->get();
            foreach ($cpls as $c) {
                // normalize to string
                $allNomors[] = (string) $c->nomor;
            }
        }

        $allNomors = array_values(array_unique($allNomors, SORT_REGULAR));
        // sort numerically if possible
        usort($allNomors, function($a, $b) {
            if (is_numeric($a) && is_numeric($b)) return $a - $b;
            return strcmp($a, $b);
        });

        // Prepare datasets
        $datasets = [];
        $colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#8E44AD', '#2ECC71']; // Colors for different siklus

        foreach ($siklusObjs as $index => $siklus) {
            $cplData = $this->calculateCplData($siklus);

            // Map nomor => rata_rata for this siklus
            $nomorMap = [];
            foreach ($cplData as $entry) {
                if (!empty($entry['cpl']) && isset($entry['cpl']->nomor)) {
                    $nomorMap[(string)$entry['cpl']->nomor] = $entry['rata_rata'];
                }
            }

            $data = [];
            foreach ($allNomors as $nomor) {
                $data[] = isset($nomorMap[$nomor]) ? $nomorMap[$nomor] : 0;
            }

            $datasets[] = [
                'label' => $siklus->tahun_mulai . ' - ' . $siklus->nama,
                'data' => $data,
                'backgroundColor' => $colors[$index % count($colors)],
                'borderColor' => $colors[$index % count($colors)],
                'borderWidth' => 1
            ];
        }

        return response()->json([
            'labels' => $allNomors,
            'datasets' => $datasets
        ]);
    }
}
