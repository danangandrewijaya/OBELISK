<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mst_mahasiswa';

    protected $fillable = [
        'nim',
        'nama',
        'prodi_id',
        'angkatan',
        'kurikulum_id',
    ];

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }
}
