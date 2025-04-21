<?php

namespace App\Imports;

use App\Models\Dosen;
use App\Models\MataKuliahKurikulum;
use App\Models\MataKuliahSemester;
use App\Models\Cpmk;
use App\Models\Cpl;
use App\Models\CPMKCPL;
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

    public function __construct($kurikulum, $previewOnly = false, $pengampuIds = [])
    {
        $this->kurikulum = $kurikulum;
        $this->previewOnly = $previewOnly;
        $this->pengampuIds = $pengampuIds;
    }

    public function sheets(): array
    {
        return [
            'CPMK-CPL' => $this, // Ensure 'CPMK-CPL' sheet is processed
            'FORM NILAI SIAP' => new NilaiImport($this->kurikulum), // Add another sheet to be processed

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
        // Skip actual processing if this is preview only
        if ($this->previewOnly) {
            return;
        }

        $cell2 = 2;
        $mataKuliahKode = explode(' ', $rows[0][2])[0];

        $tahun         = substr($rows[1][$cell2], 0, 4); // 2024-2025 -> 2024
        $semester      = strtolower($rows[2][$cell2]) === 'genap' ? 2 : 1;
        $kelas         = $rows[3][$cell2];
        // $prodi         = $rows[4][$cell2];
        $pengampu_nama = $rows[5][$cell2];
        $pengampu_nip  = 1; // belum ada di excel
        $koord_pengampu_nama= $rows[6][$cell2];
        $koord_pengampu_nip = $rows[7][$cell2];
        $sks           = $rows[8][$cell2];
        $kaprodi_nama  = $rows[9][$cell2];
        $kaprodi_nip   = $rows[10][$cell2];
        $gpm_nama      = $rows[11][$cell2];
        $gpm_nip       = $rows[12][$cell2];

        // Proses pertama: Menyimpan data dari C1 - C13 (Identitas Mata Kuliah)

        // Pengampu
        $pengampu = Dosen::where('nip', $pengampu_nip)->first();
        if (!$pengampu) {
            // throw new \Exception('Dosen pengampu tidak ditemukan');
            $pengampu = new Dosen();
            $pengampu->nip = $pengampu_nip;
            $pengampu->nama = $pengampu_nama;
            $pengampu->save();
        }

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
            // echo $row . '<br>';
            // if (empty($row['cpmk'])) continue; // Skip jika tidak ada CPMK

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

            // Simpan ke database
            if (!empty($cpmkCpl[$cpmk]['deskripsi'])) {
                $dbCpmk = Cpmk::updateOrCreate(
                    [
                        'mks_id' => $mks->id,
                        'nomor' => $cpmkCpl[$cpmk]['nomor']
                    ],
                    [
                        'deskripsi' => $cpmkCpl[$cpmk]['deskripsi'],
                        'level_taksonomi' => $cpmkCpl[$cpmk]['level_taksonomi']
                    ]
                );
            }

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

                CPMKCPL::updateOrCreate(
                    [
                        'cpmk_id' => $dbCpmk->id,
                        'cpl_id' => $cpl->id
                    ],
                    [
                        'bobot' => $cpmkCpl[$cpmk]['cpl_bobot']
                    ]
                );
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
            if(!$pengampu) {
                throw new \Exception('Mata kuliah semester tidak ditemukan');
            }
            $mks = MataKuliahSemester::create([
                'mkk_id' => $mkk->id,
                'tahun' => $tahun,
                'semester' => $semester,
                // 'pengampu_id' => $pengampu->id,
                'koord_pengampu_id' => $koord_pengampu->id,
                'gpm_id' => $gpm->id,
                // 'kaprodi' => $kaprodi->id,
            ]);
        }

        return $mks;
    }
}
