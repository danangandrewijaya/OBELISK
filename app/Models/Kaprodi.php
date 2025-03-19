<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kaprodi extends Model
{
    use HasFactory;

    protected $table = 'mst_kaprodi';

    protected $fillable = [
        'prodi_id',
        'nip',
        'nama',
        'start_date',
        'end_date',
    ];
}
