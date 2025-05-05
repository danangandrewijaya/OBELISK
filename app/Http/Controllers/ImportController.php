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
use App\Models\ImportLog;
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
     * Menampilkan halaman preview dari data yang diimport
     */
    public function previewImport(Request $request)
    {
        if ($request->isMethod('get')) {
            // Saat metode GET, periksa apakah data preview tersedia di session
            if (!$request->session()->has('import_preview_data')) {
                \Log::warning('GET Preview - No import_preview_data found in session');
                return redirect()->route('import.form')->with('error', 'Data preview tidak ditemukan. Silakan upload ulang.');
            }

            $previewData = $request->session()->get('import_preview_data');
            $tempFile = $request->session()->get('temp_file');
            $pengampus = $request->session()->get('pengampu_ids', []);
            $pengampuSession = $request->session()->get('pengampu_session');

            // Format preview data untuk tampilan
            $preview = [
                'CPMK-CPL' => $previewData,
                'FORM NILAI SIAP' => $previewData  // Include the same data for both sheets
            ];

            \Log::info('GET Preview - Successfully loaded preview data: ' . json_encode(array_keys($previewData)));

            return view('import.preview', [
                'preview' => $preview,
                'tempFile' => $tempFile,
                'pengampu_ids' => $pengampus,
                'pengampu_session' => $pengampuSession
            ]);
        }

        // Logika POST untuk memproses upload file
        $pengampus = $request->pengampu_ids ?? [];

        try {
            $validator = Validator::make($request->all(), [
                'excel_file' => 'required|mimes:xlsx,xls|max:10240',
                'pengampu_ids' => 'required|array|min:1',
                'pengampu_ids.*' => 'exists:mst_dosen,id'
            ], [
                'excel_file.required' => 'File Excel wajib diunggah',
                'excel_file.mimes' => 'File harus berformat Excel (.xlsx atau .xls)',
                'excel_file.max' => 'Ukuran file maksimal 10MB',
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

            // Proses file Excel
            $file = $request->file('excel_file');
            if (!$file) {
                \Log::error('POST Preview - No file in request');
                throw new \Exception('File tidak ditemukan dalam request.');
            }

            $fileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('temp', $fileName, 'public');
            \Log::debug('POST Preview - Stored file at: ' . $filePath);

            // Log the file upload for preview
            ImportLog::createLog('preview', [
                'file_name' => $fileName,
                'file_path' => $filePath,
                'file_type' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
                'details' => [
                    'pengampu_ids' => $pengampus
                ]
            ]);

            // Import Excel dan simpan data sesi
            \Log::debug('POST Preview - Starting Excel import process');
            $importer = new CpmkCplImport(true);
            Excel::import($importer, storage_path('app/public/' . $filePath));

            // Verifikasi data session setelah import
            \Log::debug('POST Preview - Session keys after import: ' . json_encode(array_keys($request->session()->all())));

            // Periksa apakah data preview berhasil disimpan di session
            if (!$request->session()->has('import_preview_data')) {
                \Log::error('POST Preview - Failed to store import_preview_data in session');

                // Coba simpan ulang data session secara manual
                $request->session()->put('import_preview_data', [
                    'mata_kuliah_kode' => 'MANUAL_RECOVERY',
                    'tahun' => date('Y'),
                    'semester' => 1,
                    'kelas' => 'A',
                    'error_recovery' => true
                ]);
                $request->session()->save();

                if (!$request->session()->has('import_preview_data')) {
                    \Log::critical('POST Preview - Critical session error, even manual recovery failed');
                    throw new \Exception('Gagal menyimpan data ke session. Coba periksa konfigurasi session di server Anda.');
                } else {
                    \Log::warning('POST Preview - Manual recovery succeeded but original import failed');
                    throw new \Exception('Data import tidak berhasil diproses dengan benar. Silakan coba lagi atau hubungi administrator.');
                }
            }

            // Ambil data preview dari session
            $previewData = $request->session()->get('import_preview_data');
            \Log::info('POST Preview - Successfully retrieved preview data from session');

            // Simpan data lain yang diperlukan di session
            $request->session()->put('temp_file', $filePath);
            $request->session()->put('pengampu_ids', $pengampus);

            // Persiapkan data pengampu untuk tampilan
            $pengampuSession = null;
            if (isset($previewData['pengampu_nama'])) {
                $pengampuSession = [
                    'nama' => $previewData['pengampu_nama'],
                    'nip' => $previewData['pengampu_nip'] ?? '-'
                ];
                $request->session()->put('pengampu_session', $pengampuSession);
            }

            // Format data preview untuk tampilan
            $preview = [
                'CPMK-CPL' => $previewData,
                'FORM NILAI SIAP' => $previewData  // Include nilai data in preview
            ];
            \Log::info('POST Preview - Rendering preview view with data');

            // Pastikan session disimpan sebelum merender view
            $request->session()->save();

            return view('import.preview', [
                'preview' => $preview,
                'tempFile' => $filePath,
                'pengampu_ids' => $pengampus,
                'pengampu_session' => $pengampuSession
            ]);
        } catch (\Exception $e) {
            \Log::error('POST Preview - Exception: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());

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
     * Proses import data sebenarnya
     */
    public function processImport(Request $request)
    {
        $pengampuIds = $request->input('pengampu_ids', $request->session()->get('pengampu_ids', []));

        try {
            // Verifikasi data preview tersedia
            if (!$request->session()->has('import_preview_data')) {
                throw new \Exception('Data preview tidak ditemukan. Silakan upload ulang.');
            }

            // Ambil data preview dari session
            $previewData = $request->session()->get('import_preview_data');
            $tempFile = $request->input('temp_file', $request->session()->get('temp_file'));

            // Validasi file ada
            if (!$tempFile) {
                return redirect()->route('import.form')->with('error', 'File tidak ditemukan. Silakan upload ulang.');
            }

            // Periksa apakah file fisik ada di storage
            if (!Storage::disk('public')->exists($tempFile)) {
                \Log::error('File not found in storage: ' . $tempFile);
                return redirect()->route('import.form')->with('error', 'File fisik tidak ditemukan di storage. Silakan upload ulang.');
            }

            // Mulai database transaction
            \DB::beginTransaction();

            try {
                // Instead of re-importing from Excel, use the data from session
                \Log::info('Process Import - Using data from session');
                $mks = $this->saveDataFromSession($pengampuIds, $previewData);

                // Log successful import operation
                ImportLog::createLog('confirm', [
                    'file_name' => basename($tempFile),
                    'file_path' => $tempFile,
                    'mks_id' => $mks->id,
                    'details' => [
                        'mata_kuliah_kode' => $previewData['mata_kuliah_kode'] ?? null,
                        'tahun' => $previewData['tahun'] ?? null,
                        'semester' => $previewData['semester'] ?? null,
                        'kelas' => $previewData['kelas'] ?? null,
                        'pengampu_ids' => $pengampuIds
                    ]
                ]);

                // Commit transaction jika berhasil
                \DB::commit();

                // Hapus file temporary
                Storage::disk('public')->delete($tempFile);

                // Clear session data
                $request->session()->forget([
                    'import_preview_data',
                    'temp_file',
                    'pengampu_ids',
                    'pengampu_session'
                ]);

                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Data berhasil diimpor!'
                    ]);
                }

                return redirect()->route('import.form')->with('success', 'Data berhasil diimport.');
            } catch (\Exception $e) {
                // Roll back transaction jika gagal
                \DB::rollback();

                // Still try to delete the temporary file even if import failed
                if ($tempFile && Storage::disk('public')->exists($tempFile)) {
                    Storage::disk('public')->delete($tempFile);
                    \Log::info("Deleted temporary file {$tempFile} after import error");
                }

                throw $e;
            }
        } catch (\Exception $e) {
            // Log the error in ImportLog
            ImportLog::createLog('confirm', [
                'file_name' => isset($tempFile) ? basename($tempFile) : null,
                'file_path' => $tempFile ?? null,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'details' => [
                    'exception_file' => $e->getFile(),
                    'exception_line' => $e->getLine()
                ]
            ]);

            // Additional attempt to clean up temp file in outer exception handler
            if (isset($tempFile) && Storage::disk('public')->exists($tempFile)) {
                Storage::disk('public')->delete($tempFile);
                \Log::info("Deleted temporary file {$tempFile} in outer exception handler");
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
            return redirect()->route('import.form')->with('error', 'Gagal import: ' . $e->getMessage() . ' ' . $e->getFile() . ':' . $e->getLine());
        }
    }

    /**
     * Membatalkan proses import dan menghapus data sementara
     */
    public function cancelImport(Request $request)
    {
        // Clean up temp file if exists
        $tempFile = $request->query('temp_file') ?: $request->session()->get('temp_file');
        if ($tempFile && Storage::disk('public')->exists($tempFile)) {
            // Log the canceled import
            ImportLog::createLog('cancel', [
                'file_name' => basename($tempFile),
                'file_path' => $tempFile,
                'details' => [
                    'pengampu_ids' => $request->session()->get('pengampu_ids', []),
                    'was_previewed' => $request->session()->has('import_preview_data')
                ]
            ]);

            Storage::disk('public')->delete($tempFile);
        }

        // Clear the session data
        $request->session()->forget(['import_preview_data', 'temp_file', 'pengampu_ids', 'pengampu_session']);

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
        // Use provided session data or get from session
        if ($sessionData === null) {
            $sessionData = session('import_preview_data');
            if (empty($sessionData)) {
                throw new \Exception('Data preview tidak ditemukan. Silakan upload ulang.');
            }
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
