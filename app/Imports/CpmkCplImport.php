<?php

namespace App\Imports;

use App\Models\Dosen;
use App\Models\MataKuliahKurikulum;
use App\Models\MataKuliahSemester;
use App\Models\Cpmk;
use App\Models\Cpl;
use App\Models\CpmkCpl;
use App\Models\Pengampu;
use App\Imports\NilaiImport;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\HasReferencesToOtherSheets;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Maatwebsite\Excel\Exceptions\NoSheetsFoundException;

class CpmkCplImport implements ToCollection, WithMultipleSheets, HasReferencesToOtherSheets, SkipsUnknownSheets
{
    private $kurikulum;
    private $previewOnly;
    private $pengampuIds;

    public function __construct($previewOnly = false, $pengampuIds = [])
    {
        $this->previewOnly = $previewOnly;
        $this->pengampuIds = $pengampuIds;
    }

    public function sheets(): array
    {
        return [
            'CPMK-CPL' => $this, // Ensure 'CPMK-CPL' sheet is processed
            'FORM NILAI SIAP' => new NilaiImport($this->previewOnly), // Pass the previewOnly flag

            'NILAI Partisipatif' => new NilaiLainImport(), // Add another sheet to be processed
            'NILAI Proyek' => new NilaiLainImport(), // Add another sheet to be processed
            'NILAI TUGAS' => new NilaiLainImport(), // Add another sheet to be processed
            'NILAI QUIZ' => new NilaiLainImport(), // Add another sheet to be processed
            'NILAI UTS' => new NilaiLainImport(), // Add another sheet to be processed
            'NILAI UAS' => new NilaiLainImport(), // Add another sheet to be processed

            'NILAI PRAKTEK' => new NilaiLainImport(), // Add another sheet to be processed
        ];
    }

    public function onUnknownSheet($sheetName): void
    {
        // Do nothing or handle unknown sheet as needed
        info("Skipping unknown sheet: {$sheetName}");
    }

    public function collection(Collection $rows)
    {
        $cell2 = 2;
        $mataKuliahKode = explode(' ', $rows[0][2])[0];

        // Extract CPMK-CPL data for session storage
        $cpmkCplData = [];
        foreach ($rows->slice(18, 14) as $index => $row) {
            if (!empty($row[1])) { // Only process rows with CPMK code
                $cpmk = $row[1];
                $cpmkNumber = substr($cpmk, 4);
                $cpmkDesc = $row[$cell2];

                // Find which CPL this CPMK is related to
                $cplNumber = null;
                $cplBobot = null;
                for ($i = 3; $i <= 12; $i++) {
                    if (!empty($row[$i])) {
                        $cplNumber = ($i - 3) + 1; // CPL1 starts at index 3
                        $cplBobot = $row[$i];
                        break;
                    }
                }

                $cpmkCplData[] = [
                    'kode' => $cpmk,
                    'nomor' => $cpmkNumber,
                    'deskripsi' => $cpmkDesc,
                    'cpl_number' => $cplNumber,
                    'cpl_bobot' => $cplBobot,
                    'level_taksonomi' => $row[19] ?? null
                ];
            }
        }

        // Selalu simpan data ke session terlepas dari mode preview atau import sesungguhnya
        $_SESSION['preview'] = [
            'mata_kuliah_kode' => $mataKuliahKode,
            'tahun' => substr($rows[1][$cell2], 0, 4), // 2024-2025 -> 2024
            'semester' => strtolower($rows[2][$cell2]) === 'genap' ? 2 : 1,
            'kelas' => $rows[3][$cell2],
            'pengampu_nama' => $rows[5][$cell2],
            'pengampu_nip' => isset($rows[4][$cell2]) ? $rows[4][$cell2] : null,
            'koord_pengampu_nama' => $rows[6][$cell2],
            'koord_pengampu_nip' => $rows[7][$cell2],
            'sks' => $rows[8][$cell2],
            'kaprodi_nama' => $rows[9][$cell2],
            'kaprodi_nip' => $rows[10][$cell2],
            'gpm_nama' => $rows[11][$cell2],
            'gpm_nip' => $rows[12][$cell2],
            'cpmk_cpl' => $cpmkCplData, // Save CPMK-CPL data in session
        ];

        // Skip actual processing if this is preview only
        if ($this->previewOnly) {
            return;
        }

        // Proses pertama: Menyimpan data dari C1 - C13 (Identitas Mata Kuliah)
        // Gunakan data dari session untuk proses impor, bukan dari file Excel lagi
        $tahun = $_SESSION['preview']['tahun'];
        $semester = $_SESSION['preview']['semester'];
        $kelas = $_SESSION['preview']['kelas'];
        $pengampu_nama = $_SESSION['preview']['pengampu_nama'];
        $pengampu_nip = $_SESSION['preview']['pengampu_nip'];
        $koord_pengampu_nama = $_SESSION['preview']['koord_pengampu_nama'];
        $koord_pengampu_nip = $_SESSION['preview']['koord_pengampu_nip'];
        $sks = $_SESSION['preview']['sks'];
        $kaprodi_nama = $_SESSION['preview']['kaprodi_nama'];
        $kaprodi_nip = $_SESSION['preview']['kaprodi_nip'];
        $gpm_nama = $_SESSION['preview']['gpm_nama'];
        $gpm_nip = $_SESSION['preview']['gpm_nip'];

        // Pengampu
        // $pengampu = Dosen::where('nip', $pengampu_nip)->first();
        // if (!$pengampu) {
        //     // throw new \Exception('Dosen pengampu tidak ditemukan');
        //     $pengampu = new Dosen();
        //     $pengampu->nip = $pengampu_nip;
        //     $pengampu->nama = $pengampu_nama;
        //     $pengampu->save();
        // }

        // Koordinator Pengampu
        $koord_pengampu = Dosen::where('nip', $koord_pengampu_nip)->first();
        if (!$koord_pengampu) {
            // throw new \Exception('Koordinator pengampu tidak ditemukan');
            $koord_pengampu = new Dosen();
            $koord_pengampu->nip = $koord_pengampu_nip;
            $koord_pengampu->nama = $koord_pengampu_nama;
            $koord_pengampu->save();
        }

        // GPM
        $gpm = Dosen::where('nip', $gpm_nip)->first();
        if (!$gpm) {
            // throw new \Exception('GPM tidak ditemukan');
            $gpm = new Dosen();
            $gpm->nip = $gpm_nip;
            $gpm->nama = $gpm_nama;
            $gpm->save();
        }

        // Mata Kuliah Semester
        $mks = $this->getMataKuliahSemester($mataKuliahKode, $tahun, $semester, $pengampu, $koord_pengampu, $gpm);

        // Assign selected pengampu to this MKS (if any)
        if (!empty($this->pengampuIds)) {
            // First clear existing pengampu associations for this mks
            Pengampu::where('mks_id', $mks->id)->delete();

            // Add new pengampu associations
            foreach ($this->pengampuIds as $dosenId) {
                Pengampu::create([
                    'mks_id' => $mks->id,
                    'dosen_id' => $dosenId
                ]);
            }
        }

        // Proses kedua: Menyimpan CPMK - CPL (C19 hingga C32)
        $cpmkCpl = [];
        foreach ($rows->slice(18, 14) as $row) {
            // Skip if no CPMK code or if it's empty
            if (empty($row[1])) continue;

            $cpmk = $row[1];
            $cpmkCpl[$cpmk]['nomor'] = substr($cpmk, 4);
            $cpmkCpl[$cpmk]['deskripsi'] = $row[$cell2];

            // Cek keterkaitan dengan CPL (100%)
            $cplIndeks = 1;
            // 3 - 12 adalah indeks D - M
            for ($i = 3; $i <= 12; $i++) {
                if (!empty($row[$i])) {
                    // echo $i .' '. $row[$i].'<br>';
                    $cpmkCpl[$cpmk]['cpl'] = $cplIndeks;
                    $cpmkCpl[$cpmk]['cpl_bobot'] = $row[$i];
                    break;
                }
                $cplIndeks++;
            }
            // 19 adalah indeks S
            $cpmkCpl[$cpmk]['level_taksonomi'] = $row[19];

            // Ensure deskripsi is not null before saving to database
            if (!empty($cpmk) && !empty($cpmkCpl[$cpmk]['deskripsi'])) {
                $dbCpmk = Cpmk::updateOrCreate(
                    [
                        'mks_id' => $mks->id,
                        'nomor' => $cpmkCpl[$cpmk]['nomor']
                    ],
                    [
                        'deskripsi' => $cpmkCpl[$cpmk]['deskripsi'],
                        'level_taksonomi' => $cpmkCpl[$cpmk]['level_taksonomi'] ?? null
                    ]
                );

                if (isset($cpmkCpl[$cpmk]['cpl'])) {
                    // Get kurikulum_id from MataKuliahKurikulum
                    $mkk = MataKuliahKurikulum::whereRaw('LOWER(kode) = ?', [strtolower($mataKuliahKode)])->first();
                    if (!$mkk) {
                        throw new \Exception('Mata kuliah kurikulum tidak ditemukan');
                    }

                    // Get CPL from the correct kurikulum
                    $cpl = Cpl::where('nomor', $cpmkCpl[$cpmk]['cpl'])
                        ->where('kurikulum_id', $mkk->kurikulum_id)
                        ->first();

                    if (!$cpl) {
                        throw new \Exception('CPL tidak ditemukan untuk kurikulum ini');
                    }

                    CpmkCpl::updateOrCreate(
                        [
                            'cpmk_id' => $dbCpmk->id,
                            'cpl_id' => $cpl->id
                        ],
                        [
                            'bobot' => $cpmkCpl[$cpmk]['cpl_bobot']
                        ]
                    );
                }
            } else {
                // Log a warning about skipping CPMK due to missing description
                \Log::warning("Skipping CPMK {$cpmk} import due to missing description");
            }
        }
    }

    public function getMataKuliahSemester($mataKuliahKode, $tahun, $semester, $pengampu = null, $koord_pengampu = null, $gpm = null, $kaprodi = null)
    {
        $mkk = MataKuliahKurikulum::whereRaw('LOWER(kode) = ?', [strtolower($mataKuliahKode)])->first();
        if (!$mkk) {
            throw new \Exception('Mata kuliah kurikulum tidak ditemukan');
        }

        $mks = MataKuliahSemester::where('mkk_id', $mkk->id)->where('tahun', $tahun)->where('semester', $semester)->first();
        if (!$mks) {
            // if(!$pengampu && !$koord_pengampu) {
            //     throw new \Exception('Mata kuliah semester tidak ditemukan dan pengampu/koordinator pengampu tidak tersedia');
            // }

            // Make sure koord_pengampu and gpm are not null before accessing their id
            $koord_pengampu_id = $koord_pengampu ? $koord_pengampu->id : null;
            $gpm_id = $gpm ? $gpm->id : null;

            $mks = MataKuliahSemester::create([
                'mkk_id' => $mkk->id,
                'tahun' => $tahun,
                'semester' => $semester,
                // 'pengampu_id' => $pengampu ? $pengampu->id : null,
                'koord_pengampu_id' => $koord_pengampu_id,
                'gpm_id' => $gpm_id,
                // 'kaprodi' => $kaprodi ? $kaprodi->id : null,
            ]);
        }

        return $mks;
    }
}
