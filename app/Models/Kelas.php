<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mst_kelas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'mks_id',
        'nama_kelas',
        'deskripsi',
    ];

    /**
     * Get the matakuliah semester that owns the kelas.
     */
    public function matakuliahSemester(): BelongsTo
    {
        return $this->belongsTo(MataKuliahSemester::class, 'mks_id');
    }

    /**
     * Get the nilai records associated with the kelas.
     */
    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class, 'kelas_id');
    }
}
