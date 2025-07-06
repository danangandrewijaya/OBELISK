<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siklus2 extends Model
{
    use HasFactory;

    protected $table = 'trx_siklus2';

    protected $fillable = [
        'nama',
        'kurikulum_id',
        'tahun_mulai',
        'tahun_selesai'
    ];

    // Relationship with Kurikulum
    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class);
    }

    // Relationship with SiklusPi
    public function siklusPis()
    {
        return $this->hasMany(SiklusPi::class);
    }

    // Get unique PIs associated with this Siklus
    public function pis()
    {
        return Pi::whereIn('id', function($query) {
            $query->select('pi_id')
                ->from((new SiklusPi())->getTable())
                ->where('siklus2_id', $this->id);
        })->get();
    }    // Get MataKuliahKurikulum for a specific PI in this Siklus (legacy method)
    public function getMataKuliahKurikulumsByPi($piId)
    {
        return MataKuliahKurikulum::whereIn('id', function($query) use ($piId) {
            $query->select('mata_kuliah_kurikulum_id')
                ->from((new SiklusPi())->getTable())
                ->where('siklus2_id', $this->id)
                ->where('pi_id', $piId);
        })->get();
    }

    // Get MataKuliahSemester for a specific PI in this Siklus
    public function getMataKuliahSemestersByPi($piId)
    {
        return MataKuliahSemester::whereIn('id', function($query) use ($piId) {
            $query->select('mata_kuliah_semester_id')
                ->from((new SiklusPi())->getTable())
                ->where('siklus2_id', $this->id)
                ->where('pi_id', $piId);
        })->get();
    }
}
