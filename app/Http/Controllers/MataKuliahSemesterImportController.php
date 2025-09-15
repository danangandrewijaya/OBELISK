<?php

namespace App\Http\Controllers;

use App\Imports\MataKuliahSemesterBarisImport;
use App\Models\ImportLog;
use App\Models\MataKuliahKurikulum;
use App\Models\MataKuliahSemester;
use App\Models\Pengampu;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class MataKuliahSemesterImportController extends Controller
{
    public function showForm()
    {
        return view('matkul_smt.import_form');
    }

    public function preview(Request $request)
    {
    // Debug session di awal method
    \Log::info('SESSION ON PREVIEW', session()->all());
        $rules = [
            'excel_file' => 'required|mimes:xlsx,xls|max:10240'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $file = $request->file('excel_file');
        $fileName = $file->getClientOriginalName();
        $filePath = $file->storeAs('temp', $fileName, 'public');

        ImportLog::createLog('preview', [
            'file_name' => $fileName,
            'file_path' => $filePath,
        ]);

        // Use preview mode baris importer
        $importer = new MataKuliahSemesterBarisImport(true);
        Excel::import($importer, storage_path('app/public/' . $filePath));

        // Ambil hasil parsing langsung dari importer
        $previewRows = $importer->parsedRows;
        session(['temp_file' => $filePath]);

        if (empty($previewRows)) {
            \Log::error('MataKuliahSemesterImportController::preview - parsedRows empty after import', [
                'file' => $filePath,
                'session_keys' => array_keys(session()->all())
            ]);
            return back()->with('error', 'Gagal membuat preview. Periksa log untuk detail.');
        }

        return view('matkul_smt.preview', ['previewRows' => $previewRows, 'tempFile' => $filePath]);
    }

    public function process(Request $request)
    {
        $tempFile = $request->input('temp_file', session('temp_file'));
        if (!$tempFile || !Storage::disk('public')->exists($tempFile)) {
            return redirect()->route('matkul-smt.import.form')->with('error', 'File tidak ditemukan.');
        }

        \DB::beginTransaction();
        try {
            $previewRows = session('matkul_smt_preview');
            if (empty($previewRows)) throw new \Exception('Data preview tidak ditemukan');

            // Ambil tahun dan semester dari baris header atas (misal: '2024/2025 Gasal')
            $tahun = null;
            $semester = null;
            if (!empty($previewRows)) {
                // Cari baris header tahun/semester dari session (atau bisa dari file, jika perlu)
                // Asumsi: tahun/semester diambil dari session('temp_file') atau dari file Excel
                // Untuk sekarang, ambil dari kolom paling atas previewRows jika ada
                $header = session('temp_file_header'); // jika sudah pernah disimpan
                if (!$header && isset($previewRows[0]['tahun_smt'])) {
                    $header = $previewRows[0]['tahun_smt'];
                }
                if (!$header) {
                    // fallback: ambil dari file name
                    $header = $tempFile;
                }
                // Ekstrak tahun dan semester
                if (preg_match('/(\d{4})/', $header, $m)) {
                    $tahun = $m[1];
                }
                if (preg_match('/(Gasal|Genap)/i', $header, $m)) {
                    $semester = (stripos($m[1], 'gasal') !== false) ? 1 : 2;
                }
            }

            foreach ($previewRows as $row) {
                $kode_mk = $row['kode_mk'] ?? null;
                if (!$kode_mk) continue;

                // Cari MKK
                $mkk = \App\Models\MataKuliahKurikulum::whereRaw('LOWER(kode) = ?', [strtolower($kode_mk)])->first();
                if (!$mkk) continue;

                // Update/insert MKS tanpa kolom kelas
                $mks = \App\Models\MataKuliahSemester::updateOrCreate([
                    'mkk_id' => $mkk->id,
                    'tahun' => $tahun,
                    'semester' => $semester
                ], []);

                // Tambahkan pengampu (nip1, nip2, nip3)
                foreach (['nip1','nip2','nip3'] as $nipKey) {
                    $nip = $row[$nipKey] ?? null;
                    if ($nip) {
                        $dosen = $this->getOrCreateDosen($nip, null);
                        if ($dosen) {
                            \App\Models\Pengampu::updateOrCreate([
                                'mks_id' => $mks->id,
                                'dosen_id' => $dosen->id
                            ], []);
                        }
                    }
                }
            }

            ImportLog::createLog('confirm', ['file_path' => $tempFile]);

            \DB::commit();

            Storage::disk('public')->delete($tempFile);
            session()->forget(['matkul_smt_preview', 'temp_file']);

            return redirect()->route('matkul-smt.import.form')->with('success', 'Import berhasil');
        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->route('matkul-smt.import.form')->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    private function getOrCreateDosen($nip, $nama)
    {
        if (empty($nip)) return null;
        // Jika nama kosong/null, hanya cari dosen, jangan insert
        if (empty($nama)) {
            return Dosen::where('nip', $nip)->first();
        }
        return Dosen::firstOrCreate(['nip' => $nip], ['nama' => $nama]);
    }
}
