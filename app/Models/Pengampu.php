<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengampu extends Model

{
    protected $table = 'mst_pengampu';

    protected $fillable = [
        'mks_id',
        'dosen_id',
    ];

    /**
     * Get the mata kuliah semester that this pengampu belongs to.
     */
    public function mataKuliahSemester(): BelongsTo
    {
        return $this->belongsTo(MataKuliahSemester::class, 'mks_id');
    }

    /**
     * Get the dosen that is assigned as pengampu.
     */
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }
}
