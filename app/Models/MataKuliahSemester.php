<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliahSemester extends Model
{
    use HasFactory;

    protected $table = 'mst_mata_kuliah_semester';

    protected $fillable = [
        'mkk_id',
        'tahun',
        'semester',
        'pengampu_id',
        'koord_pengampu_id',
        'gpm_id',
    ];

    public function mkk()
    {
        return $this->belongsTo(MataKuliahKurikulum::class, 'mkk_id');
    }

    public function pengampu()
    {
        return $this->belongsTo(Dosen::class, 'pengampu_id');
    }

    public function koordPengampu()
    {
        return $this->belongsTo(Dosen::class, 'koord_pengampu_id');
    }

    public function nilaiMahasiswa()
    {
        return $this->hasMany(Nilai::class, 'mks_id');
    }

    public function gpm()
    {
        return $this->belongsTo(Dosen::class, 'gpm_id');
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'mks_id');
    }

    public function cpmk()
    {
        return $this->hasMany(Cpmk::class, 'mks_id');
    }

    // public function cpmkCpl()
    // {
    //     return $this->hasMany(CPMKCPL::class, 'mks_id');
    // }

    // public function cpmkPi()
    // {
    //     return $this->hasMany(CpmkPi::class, 'mks_id');
    // }

    // public function nilaiCpmkCpl()
    // {
    //     return $this->hasMany(NilaiCpmkCpl::class, 'mks_id');
    // }

    // public function nilaiCpmkPi()
    // {
    //     return $this->hasMany(NilaiCpmkPi::class, 'mks_id');
    // }
}
