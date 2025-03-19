<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kurikulum extends Model
{
    use HasFactory;

    protected $table = 'mst_kurikulum';

    protected $fillable = [
        'nama',
        'prodi_id',
    ];
}
