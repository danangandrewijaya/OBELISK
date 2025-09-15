<?php

namespace App\Imports;

use App\Models\Dosen;
use App\Models\MataKuliahKurikulum;
use App\Models\MataKuliahSemester;
use App\Models\Pengampu;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToCollection;

class MataKuliahSemesterImport implements ToCollection
{
    private $previewOnly = false;

    public function __construct($previewOnly = false)
    {
        $this->previewOnly = $previewOnly;
    }

    public function collection(Collection $rows)
    {
        // Based on existing sheet layout used by CpmkCplImport
        $cell2 = 2;
        $mataKuliahKode = isset($rows[0][2]) ? explode(' ', $rows[0][2])[0] : null;

        $tahun = isset($rows[1][$cell2]) ? substr($rows[1][$cell2], 0, 4) : null;
        $semester = isset($rows[2][$cell2]) ? (strtolower($rows[2][$cell2]) === 'genap' ? 2 : 1) : null;
        $kelas = $rows[3][$cell2] ?? null;

        $pengampu_nip = $rows[4][$cell2] ?? null;
        $pengampu_nama = $rows[5][$cell2] ?? null;
        $koord_pengampu_nip = $rows[7][$cell2] ?? null;
        $koord_pengampu_nama = $rows[6][$cell2] ?? null;
        $gpm_nip = $rows[12][$cell2] ?? null;
        $gpm_nama = $rows[11][$cell2] ?? null;

        // Store preview to session like other imports (optional)
        $previewData = [
            'mata_kuliah_kode' => $mataKuliahKode,
            'tahun' => $tahun,
            'semester' => $semester,
            'kelas' => $kelas,
            'pengampu_nip' => $pengampu_nip,
            'pengampu_nama' => $pengampu_nama,
            'koord_pengampu_nip' => $koord_pengampu_nip,
            'koord_pengampu_nama' => $koord_pengampu_nama,
            'gpm_nip' => $gpm_nip,
            'gpm_nama' => $gpm_nama,
        ];

        // Save preview for consistency with other imports
        try {
            Session::put('import_preview_data', $previewData);
            Session::save();
        } catch (\Exception $e) {
            // ignore session errors
        }

        if ($this->previewOnly) {
            return;
        }

        // Find mkk
        $mkkQuery = MataKuliahKurikulum::whereRaw('LOWER(kode) = ?', [strtolower($mataKuliahKode)]);
        $prodiId = session('prodi_id');
        if ($prodiId) {
            $mkkQuery->whereHas('kurikulum', function ($q) use ($prodiId) {
                $q->where('prodi_id', $prodiId);
            });
        }
        $mkk = $mkkQuery->first();
        if (!$mkk) {
            throw new \Exception('Mata kuliah kurikulum tidak ditemukan');
        }

        // Find or create MataKuliahSemester by mkk_id, tahun, semester (kelas is NOT used)
        $mks = MataKuliahSemester::firstOrNew([
            'mkk_id' => $mkk->id,
            'tahun' => $tahun,
            'semester' => $semester,
        ]);

        // Update coordinator/gpm if available
        if ($koord_pengampu_nip) {
            $koord = Dosen::where('nip', $koord_pengampu_nip)->first();
            if (!$koord && $koord_pengampu_nama) {
                $koord = Dosen::create(['nip' => $koord_pengampu_nip, 'nama' => $koord_pengampu_nama]);
            }
            if ($koord) $mks->koord_pengampu_id = $koord->id;
        }

        if ($gpm_nip) {
            $gpm = Dosen::where('nip', $gpm_nip)->first();
            if (!$gpm && $gpm_nama) {
                $gpm = Dosen::create(['nip' => $gpm_nip, 'nama' => $gpm_nama]);
            }
            if ($gpm) $mks->gpm_id = $gpm->id;
        }

        // Persist MataKuliahSemester
        $mks->mkk_id = $mkk->id;
        $mks->save();

        // Handle pengampu: do not delete existing pengampu; only add missing ones.
        // Primary pengampu from sheet (single)
        if ($pengampu_nip || $pengampu_nama) {
            $pengampuDosen = null;
            if ($pengampu_nip) {
                $pengampuDosen = Dosen::where('nip', $pengampu_nip)->first();
            }
            if (!$pengampuDosen && $pengampu_nama) {
                $pengampuDosen = Dosen::whereRaw('LOWER(nama) = ?', [strtolower($pengampu_nama)])->first();
            }
            if (!$pengampuDosen && $pengampu_nip && $pengampu_nama) {
                $pengampuDosen = Dosen::create(['nip' => $pengampu_nip, 'nama' => $pengampu_nama]);
            }

            if ($pengampuDosen) {
                // Update MataKuliahSemester->pengampu_id if not set
                if (!$mks->pengampu_id) {
                    $mks->pengampu_id = $pengampuDosen->id;
                    $mks->save();
                }

                // Ensure Pengampu pivot exists
                $exists = Pengampu::where('mks_id', $mks->id)
                    ->where('dosen_id', $pengampuDosen->id)
                    ->exists();
                if (!$exists) {
                    Pengampu::create([
                        'mks_id' => $mks->id,
                        'dosen_id' => $pengampuDosen->id,
                    ]);
                }
            }
        }

        // Note: this import intentionally does not remove any existing Pengampu records. It only adds missing ones or updates MKS coordinator/gpm/pengampu references.
    }
}
