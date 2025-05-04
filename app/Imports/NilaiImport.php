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
    private $previewOnly = false;

    public function __construct($previewOnly)
    {
        $this->previewOnly = $previewOnly;
    }

    public function collection(Collection $rows)
    {
        // Pastikan session preview tersedia
        if (!isset($_SESSION['preview']) || empty($_SESSION['preview'])) {
            throw new \Exception('Preview data not found in session');
        }

        // Ambil data dari session
        $mataKuliahKode = $_SESSION['preview']['mata_kuliah_kode'];
        $tahun = $_SESSION['preview']['tahun'];
        $semester = $_SESSION['preview']['semester'];
        $kelas = $_SESSION['preview']['kelas'];

        // Extract grade data for session storage
        $rowNilaiAkhirAngka = strpos(strtolower($rows[6][8]), 'nilai akhir angka') === 0 ? 8 : 10;
        $rowNilaiAkhirHuruf = $rowNilaiAkhirAngka + 1;
        $rowNilaiBobot = $rowNilaiAkhirHuruf + 1;
        $rowOutcome = $rowNilaiBobot + 1;

        // Get CPMK details from session for row mapping
        $cpmkData = $_SESSION['preview']['cpmk_cpl'] ?? [];

        $nilaiData = [];
        $nilaiCpmkData = []; // To store individual CPMK grades

        $rows->slice(7)->each(function($row) use ($rowNilaiAkhirAngka, $rowNilaiAkhirHuruf, $rowNilaiBobot, $rowOutcome, &$nilaiData, &$nilaiCpmkData, $cpmkData) {
            $nim = $row[0];
            if (!is_null($nim)) {
                // Store main grade data
                $nilaiData[] = [
                    'nim' => $nim,
                    'nama' => preg_replace('/\s*\(.*\)$/', '', $row[1]),
                    'semester' => $row[2],
                    'status' => $row[3],
                    'nilai_akhir_angka' => $row[$rowNilaiAkhirAngka],
                    'nilai_akhir_huruf' => $row[$rowNilaiAkhirHuruf],
                    'nilai_bobot' => (float) str_replace(',', '.', $row[$rowNilaiBobot]),
                    'outcome' => $row[$rowOutcome]
                ];

                // Store CPMK grades
                // CPMK grades start from column 14 (index O)
                $mahasiswaCpmkGrades = [];

                // Calculate total number of CPMK columns
                $cpmkCount = count($cpmkData);

                if ($cpmkCount > 0) {
                    $cpmkIndex = 0;
                    $cpmkDataIndex = 0;

                    for ($i = 14; $i < 14 + ($cpmkCount * 2); $i++) {
                        if (isset($row[$i]) && !is_null($row[$i])) {
                            if ($cpmkIndex % 2 === 0) {
                                // This is a CPMK nilai_angka
                                if (isset($cpmkData[$cpmkDataIndex])) {
                                    $mahasiswaCpmkGrades[$cpmkDataIndex] = [
                                        'cpmk_kode' => $cpmkData[$cpmkDataIndex]['kode'],
                                        'cpmk_nomor' => $cpmkData[$cpmkDataIndex]['nomor'],
                                        'nilai_angka' => round((float) $row[$i], 2),
                                    ];
                                }
                            } else {
                                // This is a CPMK nilai_bobot
                                if (isset($mahasiswaCpmkGrades[$cpmkDataIndex])) {
                                    $mahasiswaCpmkGrades[$cpmkDataIndex]['nilai_bobot'] = is_numeric($row[$i]) ?
                                        round((float) str_replace(',', '.', $row[$i]), 2) : 0;
                                    $cpmkDataIndex++;
                                }
                            }
                            $cpmkIndex++;
                        }
                    }
                }

                if (!empty($mahasiswaCpmkGrades)) {
                    $nilaiCpmkData[$nim] = array_values($mahasiswaCpmkGrades);
                }
            }
        });

        // Store the grade data in session
        if (isset($_SESSION['preview'])) {
            $_SESSION['preview']['nilai_mahasiswa'] = $nilaiData;
            $_SESSION['preview']['nilai_cpmk'] = $nilaiCpmkData; // Store CPMK grades by student NIM
            $_SESSION['preview']['nilai_struktur'] = [
                'row_nilai_akhir_angka' => $rowNilaiAkhirAngka,
                'row_nilai_akhir_huruf' => $rowNilaiAkhirHuruf,
                'row_nilai_bobot' => $rowNilaiBobot,
                'row_outcome' => $rowOutcome
            ];
        }

        // Skip actual processing if this is preview only
        if ($this->previewOnly) {
            return;
        }

        // Continue with the actual import processing, using data from session
        $cpmkCplImport = new CpmkCplImport(false); // false = not preview mode
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
                $mahasiswa = Mahasiswa::create([
                    'nim' => $nim,
                    'nama' => preg_replace('/\s*\(.*\)$/', '', $row[1]),
                    'prodi_id' => 1, // belum ada di excel
                    'kurikulum_id' => 1, // sementara
                    'angkatan' => 2000 + (int) substr($nim, 6, 2),
                ]);

                // Ensure mahasiswa was successfully created
                if (!$mahasiswa) {
                    \Log::error("Failed to create or retrieve mahasiswa with NIM: $nim");
                    return false;
                }
            }

            // Nilai Terbaik - Join dengan MKS dan MKK untuk cari nilai terbaik dari MKK yang sama
            $nilaiHistoris = Nilai::from((new Nilai)->getTable().' as nilai')
                ->join((new MataKuliahSemester)->getTable().' as mks', 'mks.id', '=', 'nilai.mks_id')
                ->join((new MataKuliahKurikulum)->getTable().' as mkk', 'mkk.id', '=', 'mks.mkk_id')
                ->where('nilai.mahasiswa_id', $mahasiswa->id)
                ->where('mkk.id', function($query) use ($mks) {
                    // Ensure $mks is not null before using it
                    if ($mks) {
                        $query->select('mkk_id')
                            ->from((new MataKuliahSemester)->getTable())
                            ->where('id', $mks->id)
                            ->first();
                    } else {
                        \Log::error("MKS is null in NilaiImport.");
                        return null;
                    }
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
