<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use App\Models\Nilai;
use App\Models\NilaiCpmk;
use App\Models\NilaiCpl;
use App\Models\MataKuliahKurikulum;
use App\Models\MataKuliahSemester;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class NilaiImport implements ToCollection, WithCalculatedFormulas
{
    private $kurikulum;

    public function __construct($kurikulum)
    {
        $this->kurikulum = $kurikulum;
    }

    public function collection(Collection $rows)
    {
        // dd($rows);
        $mataKuliahKode= explode(' ', $rows[0][1])[0];
        $tahun         = substr($rows[1][1], 0, 4); // 2024-2025 -> 2024
        $semester      = strtolower($rows[2][1]) === 'genap' ? 2 : 1;
        $kelas         = $rows[3][1];

        $cpmkCplImport = new CpmkCplImport($this->kurikulum);
        $mks = $cpmkCplImport->getMataKuliahSemester($mataKuliahKode, $tahun, $semester);

        $rowNilaiAkhirAngka = strpos(strtolower($rows[6][8]), 'nilai akhir angka') === 0 ? 8 : 10;
        $rowNilaiAkhirHuruf = $rowNilaiAkhirAngka + 1;
        $rowNilaiBobot = $rowNilaiAkhirHuruf + 1;
        $rowOutcome = $rowNilaiBobot + 1;

        $rows = $rows->slice(7);
        $rows->each(function ($row) use ($mks, $kelas, $rowNilaiAkhirAngka, $rowNilaiAkhirHuruf, $rowNilaiBobot, $rowOutcome) {
            $nim = $row[0];
            if (is_null($nim)) {
                return false;
            }
            $mahasiswa = Mahasiswa::where('nim', $nim)->first();
            if (!$mahasiswa) {
                Mahasiswa::insert([
                    'nim' => $nim,
                    'nama' => preg_replace('/\s*\(.*\)$/', '', $row[1]),
                    'prodi_id' => 1, // belum ada di excel
                    'kurikulum_id' => 1, // sementara
                    'angkatan' => 2000 + (int) substr($nim, 6, 2),
                ]);
                $mahasiswa = Mahasiswa::where('nim', $nim)->first();
            }

            // Nilai Terbaik - Join dengan MKS dan MKK untuk cari nilai terbaik dari MKK yang sama
            $nilaiHistoris = Nilai::from((new Nilai)->getTable().' as nilai')
                ->join((new MataKuliahSemester)->getTable().' as mks', 'mks.id', '=', 'nilai.mks_id')
                ->join((new MataKuliahKurikulum)->getTable().' as mkk', 'mkk.id', '=', 'mks.mkk_id')
                ->where('nilai.mahasiswa_id', $mahasiswa->id)
                ->where('mkk.id', function($query) use ($mks) {
                    $query->select('mkk_id')
                        ->from((new MataKuliahSemester)->getTable())
                        ->where('id', $mks->id)
                        ->first();
                })
                ->where('nilai.is_terbaik', true)
                ->select('nilai.*')
                ->first();

            $nilaiTerbaik = true;
            if ($nilaiHistoris) {
                if ($nilaiHistoris->nilai_akhir_angka > $row[$rowNilaiAkhirAngka]) {
                    $nilaiTerbaik = false;
                }else{
                    $nilaiHistoris->update([
                        'is_terbaik' => false,
                    ]);
                }
            }

            $nilai = [
                'mahasiswa_id' => $mahasiswa->id,
                'mks_id' => $mks->id,
                'kelas' => $kelas,
                'semester' => $row[2],
                'status' => $row[3],
                'nilai_akhir_angka' => $row[$rowNilaiAkhirAngka],
                'nilai_akhir_huruf' => $row[$rowNilaiAkhirHuruf],
                'nilai_bobot' => (float) str_replace(',', '.', $row[$rowNilaiBobot]),
                'outcome' => $row[$rowOutcome],
                'is_terbaik' => $nilaiTerbaik,
            ];

            $dbNilai = Nilai::updateOrCreate(
                [
                    'mahasiswa_id' => $nilai['mahasiswa_id'],
                    'mks_id' => $nilai['mks_id'],
                    // 'kelas' => $nilai['kelas'],
                ],
                $nilai
            );

            $cpmk = $mks->cpmk()->get()->flatten();
            $cpmkIndex = 0;
            $cpmkIndex2 = 0;

            // O8
            $row->slice(14, count($cpmk) * 2)->each(function ($value, $key) use ($dbNilai, $cpmk, &$cpmkIndex, &$cpmkIndex2) {
                if (is_null($value)) {
                    return false;
                }
                if ($cpmkIndex % 2 === 0) {
                    $cpmkCplItem = $cpmk[$cpmkIndex2];
                    $nilaiCpmk = [
                        'nilai_id' => $dbNilai->id,
                        'cpmk_id' => $cpmkCplItem->id,
                        'nilai_angka' => round((float) $value, 2),
                    ];

                    NilaiCpmk::updateOrCreate(
                        [
                            'nilai_id' => $nilaiCpmk['nilai_id'],
                            'cpmk_id' => $nilaiCpmk['cpmk_id'],
                        ],
                        $nilaiCpmk
                    );
                    $cpmkIndex2++;
                } else {
                    // Update the 'nilai_bobot' for the previous 'nilai_cpmk'
                    $cpmkCplItem = $cpmk[$cpmkIndex2 - 1];
                    // Convert and validate nilai_bobot
                    $nilaiBobot = is_numeric($value) ? round((float) str_replace(',', '.', $value), 2) : 0;

                    NilaiCpmk::where([
                        'nilai_id' => $dbNilai->id,
                        'cpmk_id' => $cpmkCplItem->id,
                    ])->update(['nilai_bobot' => $nilaiBobot]);
                }
                $cpmkIndex++;
            });
        });

        $nilaiCpl = new NilaiCpl();
        $nilaiCpl->createNilaiCplFromMks($mks);
    }
}
