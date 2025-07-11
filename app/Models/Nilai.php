<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'trx_nilai';

    protected $fillable = [
        'mahasiswa_id',
        'mks_id',
        'kelas',
        'kelas_id',
        'semester',
        'status',
        'nilai_akhir_angka',
        'nilai_akhir_huruf',
        'nilai_bobot',
        'outcome',
        'keterangan',
        'is_terbaik'
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function mks()
    {
        return $this->belongsTo(MataKuliahSemester::class, 'mks_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function nilaiCpmk()
    {
        return $this->hasMany(NilaiCpmk::class, 'nilai_id');
    }

    public function nilaiCpl()
    {
        return $this->hasMany(NilaiCpl::class, 'nilai_id');
    }
}
