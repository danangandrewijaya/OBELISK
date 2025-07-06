<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiklusPi extends Model
{
    use HasFactory;

    protected $table = 'trx_siklus_pi';

    protected $fillable = [
        'siklus2_id',
        'pi_id',
        'mata_kuliah_semester_id'
    ];

    // Relationship with Siklus
    public function siklus()
    {
        return $this->belongsTo(Siklus::class);
    }

    // Relationship with PI
    public function pi()
    {
        return $this->belongsTo(Pi::class);
    }

    // Relationship with MataKuliahSemester
    public function mataKuliahSemester()
    {
        return $this->belongsTo(MataKuliahSemester::class);
    }
}
