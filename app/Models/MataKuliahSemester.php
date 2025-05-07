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

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the slug attribute.
     *
     * @return string
     */
    public function getSlugAttribute()
    {
        $kode = $this->mkk ? $this->mkk->kode : 'unknown';
        return "{$kode}-{$this->tahun}-{$this->semester}";
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        if ($field === 'slug' || $field === null) {
            // Parse the slug to extract kode, tahun, and semester
            $parts = explode('-', $value);
            if (count($parts) >= 3) {
                $kode = $parts[0];
                $tahun = $parts[1];
                $semester = $parts[2];

                return $this->whereHas('mkk', function ($query) use ($kode) {
                    $query->where('kode', $kode);
                })->where('tahun', $tahun)
                  ->where('semester', $semester)
                  ->first();
            }
            return null;
        }

        return parent::resolveRouteBinding($value, $field);
    }

    public function mkk()
    {
        return $this->belongsTo(MataKuliahKurikulum::class, 'mkk_id');
    }

    public function pengampu()
    {
        return $this->belongsTo(Dosen::class, 'pengampu_id');
    }

    public function pengampus()
    {
        return $this->hasMany(Pengampu::class, 'mks_id');
    }

    public function pengampuDosens()
    {
        return $this->hasManyThrough(
            Dosen::class,
            Pengampu::class,
            'mks_id', // Foreign key on pengampu table
            'id',     // Foreign key on dosen table
            'id',     // Local key on matakuliah_semester table
            'dosen_id' // Local key on pengampu table
        );
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
