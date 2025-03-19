<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\HasReferencesToOtherSheets;
use Maatwebsite\Excel\Concerns\ToArray;

class NilaiLainImport implements ToArray, HasReferencesToOtherSheets
{
    public function array(array $rows)
    {
        // dd($rows); // Lihat apakah sudah mendapatkan nilai, bukan rumus
    }
}
