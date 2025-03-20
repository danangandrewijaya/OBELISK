<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cpl extends Model
{
    use HasFactory;

    protected $table = 'mst_cpl';

    protected $fillable = [
        'nomor',
        'nama',
        'deskripsi',
        'prodi_id',
    ];

    public function cpmkCpl(): HasMany
    {
        return $this->hasMany(CpmkCpl::class, 'cpl_id');
    }

    public function cpmks(): BelongsToMany
    {
        return $this->belongsToMany(Cpmk::class, 'trx_cpmk_cpl', 'cpl_id', 'cpmk_id');
    }
}
