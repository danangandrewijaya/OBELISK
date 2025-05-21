<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siklus extends Model
{
    use HasFactory;

    protected $table = 'trx_siklus';

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

    // Relationship with SiklusCpl
    public function siklusCpls()
    {
        return $this->hasMany(SiklusCpl::class);
    }

    // Get unique CPLs associated with this Siklus
    public function cpls()
    {
        return Cpl::whereIn('id', function($query) {
            $query->select('cpl_id')
                ->from((new SiklusCpl())->getTable())
                ->where('siklus_id', $this->id);
        })->get();
    }    // Get MataKuliahKurikulum for a specific CPL in this Siklus (legacy method)
    public function getMataKuliahKurikulumsByCpl($cplId)
    {
        return MataKuliahKurikulum::whereIn('id', function($query) use ($cplId) {
            $query->select('mata_kuliah_kurikulum_id')
                ->from((new SiklusCpl())->getTable())
                ->where('siklus_id', $this->id)
                ->where('cpl_id', $cplId);
        })->get();
    }

    // Get MataKuliahSemester for a specific CPL in this Siklus
    public function getMataKuliahSemestersByCpl($cplId)
    {
        return MataKuliahSemester::whereIn('id', function($query) use ($cplId) {
            $query->select('mata_kuliah_semester_id')
                ->from((new SiklusCpl())->getTable())
                ->where('siklus_id', $this->id)
                ->where('cpl_id', $cplId);
        })->get();
    }
}
