<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToCollection;

class MataKuliahSemesterBarisImport implements ToCollection
{
    private $previewOnly = false;
    public $parsedRows = [];

    public function __construct($previewOnly = false)
    {
        $this->previewOnly = $previewOnly;
    }

    public function collection(Collection $rows)
    {
        \Log::info('DEBUG IMPORT rows', ['rows' => $rows->toArray()]);
        $data = [];
        // Mulai dari baris ke-6 (index 5), sesuai header pada lampiran
        $parsedRows = [];
        foreach ($rows as $i => $row) {
            \Log::info('IMPORT LOOP ROW', ['i' => $i, 'row' => $row]);
            // Skip header and irrelevant rows
            if ($i < 4) continue;
            // Hanya proses baris yang punya kode mk dan kelas
            if (empty($row[2]) || empty($row[3])) continue;

            $parsedRows[] = [
                'waktu' => $row[0],
                'nama_mk' => $row[1],
                'kode_mk' => $row[2],
                'kelas' => $row[3],
                'sks' => $row[4],
                'kuota' => $row[5],
                'peserta' => $row[6],
                'dibuka' => $row[7],
                'pengampu1' => isset($row[8]) ? $row[8] : null,
                'pengampu2' => isset($row[9]) ? $row[9] : null,
                'pengampu3' => isset($row[10]) ? $row[10] : null,
                'nip1' => isset($row[11]) ? $row[11] : null,
                'nip2' => isset($row[12]) ? $row[12] : null,
                'nip3' => isset($row[13]) ? $row[13] : null,
            ];
        }

        \Log::info('IMPORT PARSED ROWS RESULT', ['parsedRows' => $parsedRows]);
        $this->parsedRows = $parsedRows;
        session(['matkul_smt_preview' => $parsedRows]);
    // dd(session()->all());
    }
}
