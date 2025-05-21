<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiklusCpl extends Model
{
    use HasFactory;

    protected $table = 'trx_siklus_cpl';    protected $fillable = [
        'siklus_id',
        'cpl_id',
        'mata_kuliah_semester_id'
    ];

    // Relationship with Siklus
    public function siklus()
    {
        return $this->belongsTo(Siklus::class);
    }

    // Relationship with CPL
    public function cpl()
    {
        return $this->belongsTo(Cpl::class);
    }    // Relationship with MataKuliahSemester
    public function mataKuliahSemester()
    {
        return $this->belongsTo(MataKuliahSemester::class);
    }
}
