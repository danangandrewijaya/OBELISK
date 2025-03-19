<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cpmk extends Model
{
    use HasFactory;

    protected $table = 'mst_cpmk';

    protected $fillable = [
        'mks_id',
        'nomor',
        'deskripsi',
        'level_taksonomi',
    ];

    public function mks()
    {
        return $this->belongsTo(MataKuliahSemester::class, 'mks_id');
    }

    public function cpmkCpl()
    {
        return $this->hasMany(CpmkCpl::class, 'cpmk_id');
    }

    public function cpmkPi()
    {
        return $this->hasMany(CpmkPi::class, 'cpmk_id');
    }

    public function nilaiCpmkCpl()
    {
        return $this->hasMany(NilaiCpmkCpl::class, 'cpmk_id');
    }

    public function nilaiCpmkPi()
    {
        // return $this->hasMany(NilaiCpmkPi::class, 'cpmk_id');
    }

    public function getNilaiCpmkCpl($mksId, $cpmkId)
    {
        return NilaiCpmkCpl::where('mks_id', $mksId)->where('cpmk_id', $cpmkId)->first();
    }

    public function getNilaiCpmkPi($mksId, $cpmkId)
    {
        // return NilaiCpmkPi::where('mks_id', $mksId)->where('cpmk_id', $cpmkId)->first();
    }

    public function getNilaiCpmkCplByCpl($mksId, $cplId)
    {
        return NilaiCpmkCpl::where('mks_id', $mksId)->where('cpl_id', $cplId)->first();
    }

    public function getNilaiCpmkPiByPi($mksId, $piId)
    {
        // return NilaiCpmkPi::where('mks_id', $mksId)->where('pi_id', $piId)->first();
    }
}
