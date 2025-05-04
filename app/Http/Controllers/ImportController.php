<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CpmkCplImport;
use App\Imports\CpmkImport;
use App\Models\Cpl;
use App\Models\Cpmk;
use App\Models\CpmkCpl;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\MataKuliahKurikulum;
use App\Models\MataKuliahSemester;
use App\Models\Nilai;
use App\Models\NilaiCpl;
use App\Models\NilaiCpmk;
use App\Models\Pengampu;
use Maatwebsite\Excel\Excel as ExcelFormat;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Settings;

class ImportController extends Controller
{
    /**
     * Menampilkan formulir import
     */
    public function showImportForm()
    {
        $dosens = Dosen::orderBy('nama')->get();
        return view('import.form', compact('dosens'));
    }

    /**
     * Memproses file upload dan menampilkan preview data yang akan diimpor
     */
    public function previewImport(Request $request)
    {
        if ($request->isMethod('get')) {
            // Saat metode GET, periksa apakah data preview tersedia di Laravel session
            if (!$request->session()->has('preview_data')) {
                return redirect()->route('import.form')->with('error', 'Data preview tidak ditemukan. Silakan upload ulang.');
            }

            $previewData = $request->session()->get('preview_data');
            $tempFile = $request->session()->get('temp_file');
            $pengampus = $request->session()->get('pengampu_ids', []);
            $pengampuSession = $request->session()->get('pengampu_session');
            
            // Jika di Laravel session tidak ada $_SESSION['preview'], gunakan data dari Laravel session
            if (!isset($_SESSION['preview']) && $request->session()->has('preview_complete')) {
                $_SESSION['preview'] = $request->session()->get('preview_complete');
            }

            return view('import.preview', [
                'preview' => $previewData,
                'tempFile' => $tempFile,
                'pengampu_ids' => $pengampus,
                'pengampu_session' => $pengampuSession
            ]);
        }

        // Logika POST tetap seperti sebelumnya
        $pengampus = $request->pengampu_ids ?? [];

        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|mimes:xlsx,xls|max:10240',
                'pengampu_ids' => 'required|array|min:1',
                'pengampu_ids.*' => 'exists:mst_dosen,id'
            ], [
                'pengampu_ids.required' => 'Pilihan pengampu wajib diisi',
                'pengampu_ids.min' => 'Pilih minimal satu pengampu'
            ]);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $validator->errors()->first()
                    ], 422);
                }
                return back()->withErrors($validator);
            }

            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('temp', $fileName, 'public');

            $importer = new CpmkCplImport(true);
            Excel::import($importer, storage_path('app/public/' . $filePath));

            if (isset($_SESSION['preview'])) {
                // Simpan data dari $_SESSION ke Laravel session
                $request->session()->put('preview_data', $_SESSION['preview']);
                $request->session()->put('preview_complete', $_SESSION['preview']);
                
                // Simpan data pengampu dari Excel
                $pengampuSession = null;
                if (isset($_SESSION['preview']['pengampu_nama'])) {
                    $pengampuSession = [
                        'nama' => $_SESSION['preview']['pengampu_nama'],
                        'nip' => $_SESSION['preview']['pengampu_nip'] ?? '-'
                    ];
                    $request->session()->put('pengampu_session', $pengampuSession);
                }
            }

            $reader = IOFactory::createReader('Xlsx');
            $spreadsheet = $reader->load(storage_path('app/public/' . $filePath));
            $previewData = [];

            foreach (['CPMK-CPL', 'FORM NILAI SIAP'] as $sheetName) {
                if ($spreadsheet->getSheetByName($sheetName)) {
                    $worksheet = $spreadsheet->getSheetByName($sheetName);
                    $sheetArray = $worksheet->toArray(null, true, true, true);
                    $previewData[$sheetName] = array_values($sheetArray);
                }
            }

            $request->session()->put('temp_file', $filePath);
            $request->session()->put('pengampu_ids', $pengampus);

            // Tambahkan data pengampu dari session untuk POST
            $pengampuSession = null;
            if (isset($_SESSION['preview']['pengampu_nama'])) {
                $pengampuSession = [
                    'nama' => $_SESSION['preview']['pengampu_nama'],
                    'nip' => $_SESSION['preview']['pengampu_nip'] ?? '-'
                ];
            }

            return view('import.preview', [
                'preview' => $previewData,
                'tempFile' => $filePath,
                'pengampu_ids' => $pengampus,
                'pengampu_session' => $pengampuSession
            ]);
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Memproses data import setelah preview dikonfirmasi
     */
    public function processImport(Request $request)
    {
        $pengampus = $request->pengampu_ids ?? [];

        try {
            // Konfirmasi diterima - proses data dari Laravel session
            if (!$request->session()->has('preview_data')) {
                throw new \Exception('Data preview tidak ditemukan. Silakan upload ulang.');
            }

            // Get preview data from Laravel session
            $previewData = $request->session()->get('preview_data');

            // Start a database transaction to ensure all data is saved together
            \DB::beginTransaction();

            try {
                // Save the data from session to database using services
                $this->saveDataFromSession($pengampus, $previewData);

                // Commit the transaction if everything was successful
                \DB::commit();

                // Clean up temp file and session data
                $tempFile = $request->input('temp_file');
                if ($tempFile && Storage::disk('public')->exists($tempFile)) {
                    Storage::disk('public')->delete($tempFile);
                }

                // Clear the session data after successful import
                $request->session()->forget('preview_data');

                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Data berhasil diimpor!'
                    ]);
                }

                return redirect()->route('import.form')->with('success', 'Data berhasil diimpor!');
            } catch (\Exception $e) {
                // Roll back the transaction if there was an error
                \DB::rollback();
                throw $e; // Re-throw the exception to be caught by the outer catch block
            }
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Membatalkan proses import dan menghapus data sementara
     */
    public function cancelImport(Request $request)
    {
        // Clean up temp file if exists
        $tempFile = $request->query('temp_file');
        if ($tempFile && Storage::disk('public')->exists($tempFile)) {
            Storage::disk('public')->delete($tempFile);
        }

        // Clear the session data
        $request->session()->forget('preview_data');

        return redirect()->route('import.form')->with('info', 'Import dibatalkan.');
    }

    // Method lama untuk backward compatibility
    public function importExcel(Request $request)
    {
        if ($request->has('confirm')) {
            return $this->processImport($request);
        } else {
            return $this->previewImport($request);
        }
    }

    /**
     * Save data from session to database
     */
    private function saveDataFromSession($pengampuIds = [], $sessionData = null)
    {
        // Use provided session data or try to get from $_SESSION as fallback
        if ($sessionData === null) {
            if (!isset($_SESSION['preview']) || empty($_SESSION['preview'])) {
                throw new \Exception('Data preview tidak ditemukan. Silakan upload ulang.');
            }
            $sessionData = $_SESSION['preview'];
        }

        // 1. Get or create necessary dosen records
        $pengampu = $this->getOrCreateDosen($sessionData['pengampu_nip'], $sessionData['pengampu_nama']);
        $koordPengampu = $this->getOrCreateDosen($sessionData['koord_pengampu_nip'], $sessionData['koord_pengampu_nama']);
        $gpm = $this->getOrCreateDosen($sessionData['gpm_nip'], $sessionData['gpm_nama']);
        $kaprodi = $this->getOrCreateDosen($sessionData['kaprodi_nip'], $sessionData['kaprodi_nama']);

        // 2. Get or create MataKuliahSemester
        $mataKuliahKode = $sessionData['mata_kuliah_kode'];
        $tahun = $sessionData['tahun'];
        $semester = $sessionData['semester'];
        $kelas = $sessionData['kelas'];

        $mkk = MataKuliahKurikulum::whereRaw('LOWER(kode) = ?', [strtolower($mataKuliahKode)])->first();
        if (!$mkk) {
            throw new \Exception("Mata kuliah dengan kode $mataKuliahKode tidak ditemukan dalam kurikulum.");
        }

        $mks = MataKuliahSemester::updateOrCreate(
            [
                'mkk_id' => $mkk->id,
                'tahun' => $tahun,
                'semester' => $semester
            ],
            [
                'koord_pengampu_id' => $koordPengampu ? $koordPengampu->id : null,
                'gpm_id' => $gpm ? $gpm->id : null,
                // Other fields if needed
            ]
        );

        // 3. Save pengampu associations
        if (!empty($pengampuIds)) {
            // Clear existing pengampu associations
            Pengampu::where('mks_id', $mks->id)->delete();

            // Add new pengampu associations
            foreach ($pengampuIds as $dosenId) {
                Pengampu::create([
                    'mks_id' => $mks->id,
                    'dosen_id' => $dosenId
                ]);
            }
        }

        // 4. Process CPMK-CPL data
        $cpmkMap = []; // Keep track of CPMK codes to their database IDs
        if (!empty($sessionData['cpmk_cpl'])) {
            foreach ($sessionData['cpmk_cpl'] as $cpmkData) {
                // Skip CPMK if description is missing or empty
                if (empty($cpmkData['deskripsi'])) {
                    \Log::warning("Skipping CPMK {$cpmkData['kode']} import due to missing description");
                    continue;
                }

                // Create CPMK
                $cpmk = Cpmk::updateOrCreate(
                    [
                        'mks_id' => $mks->id,
                        'nomor' => $cpmkData['nomor']
                    ],
                    [
                        'deskripsi' => $cpmkData['deskripsi'],
                        'level_taksonomi' => $cpmkData['level_taksonomi'] ?? null
                    ]
                );

                // Store the mapping of CPMK code to database ID for later use
                $cpmkMap[$cpmkData['kode']] = $cpmk->id;

                // Link to CPL if applicable
                if (!empty($cpmkData['cpl_number'])) {
                    $cpl = Cpl::where('nomor', $cpmkData['cpl_number'])
                        ->where('kurikulum_id', $mkk->kurikulum_id)
                        ->first();

                    if ($cpl) {
                        CpmkCpl::updateOrCreate(
                            [
                                'cpmk_id' => $cpmk->id,
                                'cpl_id' => $cpl->id
                            ],
                            [
                                'bobot' => $cpmkData['cpl_bobot'] ?? 1
                            ]
                        );
                    }
                }
            }
        }

        // 5. Process student grades if available
        if (!empty($sessionData['nilai_mahasiswa'])) {
            // Get a list of all CPMK for this mks
            $cpmks = Cpmk::where('mks_id', $mks->id)->get();

            foreach ($sessionData['nilai_mahasiswa'] as $nilaiData) {
                // Get or create the student
                $mahasiswa = Mahasiswa::firstOrCreate(
                    ['nim' => $nilaiData['nim']],
                    [
                        'nama' => $nilaiData['nama'],
                        'prodi_id' => 1, // Default, can be updated later
                        'kurikulum_id' => $mkk->kurikulum_id,
                        'angkatan' => 2000 + (int) substr($nilaiData['nim'], 6, 2)
                    ]
                );

                // Check for historical best grade
                $nilaiHistoris = Nilai::from((new Nilai)->getTable().' as nilai')
                    ->join((new MataKuliahSemester)->getTable().' as mks', 'mks.id', '=', 'nilai.mks_id')
                    ->join((new MataKuliahKurikulum)->getTable().' as mkk', 'mkk.id', '=', 'mks.mkk_id')
                    ->where('nilai.mahasiswa_id', $mahasiswa->id)
                    ->where('mkk.id', $mkk->id)
                    ->where('nilai.is_terbaik', true)
                    ->select('nilai.*')
                    ->first();

                $nilaiTerbaik = true;
                if ($nilaiHistoris) {
                    if ($nilaiHistoris->nilai_akhir_angka > $nilaiData['nilai_akhir_angka']) {
                        $nilaiTerbaik = false;
                    } else {
                        $nilaiHistoris->update(['is_terbaik' => false]);
                    }
                }

                // Create or update nilai record
                $dbNilai = Nilai::updateOrCreate(
                    [
                        'mahasiswa_id' => $mahasiswa->id,
                        'mks_id' => $mks->id
                    ],
                    [
                        'kelas' => $kelas,
                        'semester' => $nilaiData['semester'],
                        'status' => $nilaiData['status'],
                        'nilai_akhir_angka' => $nilaiData['nilai_akhir_angka'],
                        'nilai_akhir_huruf' => $nilaiData['nilai_akhir_huruf'],
                        'nilai_bobot' => $nilaiData['nilai_bobot'],
                        'outcome' => $nilaiData['outcome'],
                        'is_terbaik' => $nilaiTerbaik
                    ]
                );

                // Process CPMK grades
                if (isset($sessionData['nilai_cpmk'][$nilaiData['nim']])) {
                    $cpmkGrades = $sessionData['nilai_cpmk'][$nilaiData['nim']];

                    foreach ($cpmkGrades as $cpmkGrade) {
                        // Find the actual CPMK ID using the map or by looking up
                        $cpmkId = $cpmkMap[$cpmkGrade['cpmk_kode']] ?? null;

                        // If we couldn't find it in the map, try looking it up directly
                        if (!$cpmkId) {
                            $cpmkObj = $cpmks->where('nomor', $cpmkGrade['cpmk_nomor'])->first();
                            $cpmkId = $cpmkObj ? $cpmkObj->id : null;
                        }

                        if ($cpmkId) {
                            NilaiCpmk::updateOrCreate(
                                [
                                    'nilai_id' => $dbNilai->id,
                                    'cpmk_id' => $cpmkId
                                ],
                                [
                                    'nilai_angka' => $cpmkGrade['nilai_angka'],
                                    'nilai_bobot' => $cpmkGrade['nilai_bobot'] ?? 0
                                ]
                            );
                        }
                    }
                }
            }

            // After all grades are saved, recalculate CPL values
            $nilaiCpl = new NilaiCpl();
            $nilaiCpl->createNilaiCplFromMks($mks);
        }

        return $mks;
    }

    /**
     * Get existing dosen or create a new one
     */
    private function getOrCreateDosen($nip, $nama)
    {
        if (empty($nip)) {
            return null;
        }

        return Dosen::firstOrCreate(
            ['nip' => $nip],
            ['nama' => $nama]
        );
    }

    public function index(Request $request)
    {
        $mks = MataKuliahSemester::find(4);
        $cpmk_cpl = $mks->cpmk()->get()->flatten();
        dd($cpmk_cpl);
    }
}
