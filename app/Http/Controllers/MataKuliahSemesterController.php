<?php

namespace App\Http\Controllers;

use App\DataTables\MatakuliahSemesterDataTable;
use App\Models\Dosen;
use App\Models\MataKuliahKurikulum;
use App\Models\MataKuliahSemester;
use App\Models\Pengampu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MataKuliahSemesterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, MatakuliahSemesterDataTable $dataTable)
    {
        $years = MataKuliahSemester::distinct()->pluck('tahun')->sort()->values();
        $semesters = MataKuliahSemester::distinct()->pluck('semester')->sort()->values();

        return $dataTable->render('matakuliah-semester.index', compact('years', 'semesters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $matakuliahs = MataKuliahKurikulum::orderBy('kode')->get();
        $dosens = Dosen::orderBy('nama')->get();
        $tahunOptions = range(date('Y') - 5, date('Y') + 1);
        $semesterOptions = [1, 2];

        return view('matakuliah-semester.create', compact('matakuliahs', 'dosens', 'tahunOptions', 'semesterOptions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'mkk_id' => 'required|exists:mst_mata_kuliah_kurikulum,id',
            'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'semester' => 'required|in:1,2',
            'koord_pengampu_id' => 'required|exists:mst_dosen,id',
            'gpm_id' => 'required|exists:mst_dosen,id',
            'pengampu_ids' => 'required|array|min:1',
            'pengampu_ids.*' => 'required|exists:mst_dosen,id',
        ], [
            'mkk_id.required' => 'Mata kuliah harus dipilih',
            'tahun.required' => 'Tahun harus diisi',
            'semester.required' => 'Semester harus dipilih',
            'koord_pengampu_id.required' => 'Koordinator pengampu harus dipilih',
            'gpm_id.required' => 'GPM harus dipilih',
            'pengampu_ids.required' => 'Minimal satu dosen pengampu harus dipilih',
            'pengampu_ids.min' => 'Minimal satu dosen pengampu harus dipilih',
        ]);

        // Check if the combination of mkk_id, tahun, and semester already exists
        $exists = MataKuliahSemester::where('mkk_id', $request->mkk_id)
            ->where('tahun', $request->tahun)
            ->where('semester', $request->semester)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'mkk_id' => 'Mata kuliah ini sudah ada untuk tahun dan semester yang dipilih',
            ])->withInput();
        }

        try {
            DB::beginTransaction();

            // Log the data for debugging
            \Log::info('Creating matakuliah semester with data:', [
                'mkk_id' => $request->mkk_id,
                'tahun' => $request->tahun,
                'semester' => $request->semester,
                'koord_pengampu_id' => $request->koord_pengampu_id,
                'gpm_id' => $request->gpm_id,
                'pengampu_id' => $request->pengampu_ids[0],
                'pengampu_ids' => $request->pengampu_ids,
            ]);

            $matakuliahSemester = MataKuliahSemester::create([
                'mkk_id' => $request->mkk_id,
                'tahun' => $request->tahun,
                'semester' => $request->semester,
                'koord_pengampu_id' => $request->koord_pengampu_id,
                'gpm_id' => $request->gpm_id,
            ]);

            // Log the created model
            \Log::info('Matakuliah semester created with ID: ' . ($matakuliahSemester->id ?? 'null'));

            if (!$matakuliahSemester || !$matakuliahSemester->id) {
                throw new \Exception('Gagal menyimpan data mata kuliah semester. Data tidak tersimpan.');
            }

            foreach ($request->pengampu_ids as $dosenId) {
                $pengampu = Pengampu::updateOrCreate(
                    [
                        'mks_id' => $matakuliahSemester->id,
                        'dosen_id' => $dosenId,
                    ],
                    []
                );

                \Log::info('Created pengampu:', [
                    'mks_id' => $matakuliahSemester->id,
                    'dosen_id' => $dosenId,
                    'pengampu_id' => $pengampu->id ?? 'null'
                ]);
            }

            DB::commit();

            return redirect()->route('master.matakuliah-semester.index')
                ->with('success', 'Data mata kuliah semester berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error creating matakuliah semester: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MataKuliahSemester  $mataKuliahSemester
     * @return \Illuminate\Http\Response
     */
    public function show(MataKuliahSemester $matakuliahSemester)
    {
        $matakuliahSemester->load([
            'mkk',
            'koordPengampu',
            'gpm',
            'pengampuDosens'
        ]);

        return view('matakuliah-semester.show', compact('matakuliahSemester'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MataKuliahSemester  $mataKuliahSemester
     * @return \Illuminate\Http\Response
     */
    public function edit(MataKuliahSemester $matakuliahSemester)
    {
        $matakuliahs = MataKuliahKurikulum::orderBy('kode')->get();
        $dosens = Dosen::orderBy('nama')->get();
        $tahunOptions = range(date('Y') - 5, date('Y') + 1);
        $semesterOptions = [1, 2];

        $selectedPengampuIds = $matakuliahSemester->pengampus->pluck('dosen_id')->toArray();

        return view('matakuliah-semester.edit', compact('matakuliahSemester', 'matakuliahs', 'dosens', 'tahunOptions', 'semesterOptions', 'selectedPengampuIds'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MataKuliahSemester  $mataKuliahSemester
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MataKuliahSemester $matakuliahSemester)
    {
        // Log the request data for debugging
        \Log::info('Update request data:', [
            'request_data' => $request->all(),
            'model_id' => $matakuliahSemester->id,
            'pengampu_ids' => $request->input('pengampu_ids')
        ]);

        $request->validate([
            'mkk_id' => 'required|exists:mst_mata_kuliah_kurikulum,id',
            'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'semester' => 'required|in:1,2',
            'koord_pengampu_id' => 'required|exists:mst_dosen,id',
            'gpm_id' => 'required|exists:mst_dosen,id',
            'pengampu_ids' => 'required|array|min:1',
            'pengampu_ids.*' => 'required|exists:mst_dosen,id',
        ], [
            'mkk_id.required' => 'Mata kuliah harus dipilih',
            'tahun.required' => 'Tahun harus diisi',
            'semester.required' => 'Semester harus dipilih',
            'koord_pengampu_id.required' => 'Koordinator pengampu harus dipilih',
            'gpm_id.required' => 'GPM harus dipilih',
            'pengampu_ids.required' => 'Minimal satu dosen pengampu harus dipilih',
            'pengampu_ids.min' => 'Minimal satu dosen pengampu harus dipilih',
        ]);

        // Check if the combination of mkk_id, tahun, and semester already exists for other records
        $exists = MataKuliahSemester::where('mkk_id', $request->mkk_id)
            ->where('tahun', $request->tahun)
            ->where('semester', $request->semester)
            ->where('id', '!=', $matakuliahSemester->id)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'mkk_id' => 'Mata kuliah ini sudah ada untuk tahun dan semester yang dipilih',
            ])->withInput();
        }

        try {
            DB::beginTransaction();

            $matakuliahSemester->update([
                'mkk_id' => $request->mkk_id,
                'tahun' => $request->tahun,
                'semester' => $request->semester,
                'koord_pengampu_id' => $request->koord_pengampu_id,
                'gpm_id' => $request->gpm_id,
            ]);

            // Get current pengampu IDs to remove ones that are no longer selected
            $currentPengampuIds = $matakuliahSemester->pengampus()->pluck('dosen_id')->toArray();
            $pengampuIdsToRemove = array_diff($currentPengampuIds, $request->pengampu_ids);

            // Remove pengampus that are no longer selected
            if (!empty($pengampuIdsToRemove)) {
                $matakuliahSemester->pengampus()->whereIn('dosen_id', $pengampuIdsToRemove)->delete();
            }

            // Update or create pengampus
            foreach ($request->pengampu_ids as $dosenId) {
                Pengampu::updateOrCreate(
                    [
                        'mks_id' => $matakuliahSemester->id,
                        'dosen_id' => $dosenId,
                    ],
                    []
                );
            }

            DB::commit();

            return redirect()->route('master.matakuliah-semester.index')
                ->with('success', 'Data mata kuliah semester berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MataKuliahSemester  $mataKuliahSemester
     * @return \Illuminate\Http\Response
     */
    public function destroy(MataKuliahSemester $matakuliahSemester)
    {
        try {
            DB::beginTransaction();

            if ($matakuliahSemester->nilaiMahasiswa()->count() > 0) {
                return back()->with('error', 'Tidak dapat menghapus data karena masih memiliki data nilai mahasiswa terkait.');
            }

            if ($matakuliahSemester->cpmk()->count() > 0) {
                return back()->with('error', 'Tidak dapat menghapus data karena masih memiliki data CPMK terkait.');
            }

            $matakuliahSemester->pengampus()->delete();

            $matakuliahSemester->delete();

            DB::commit();

            return redirect()->route('master.matakuliah-semester.index')
                ->with('success', 'Data mata kuliah semester berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
