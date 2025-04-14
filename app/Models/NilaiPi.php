<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NilaiPi extends Model
{
    protected $table = 'trx_nilai_pi';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function nilai(): BelongsTo
    {
        return $this->belongsTo(Nilai::class);
    }

    public function pi(): BelongsTo
    {
        return $this->belongsTo(Pi::class);
    }
}
