<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiCpmk extends Model
{
    use HasFactory;

    protected $table = 'trx_nilai_cpmk';

    protected $fillable = [
        'nilai_id',
        'cpmk_id',
        'nilai_angka',
        'nilai_bobot',
    ];
}
