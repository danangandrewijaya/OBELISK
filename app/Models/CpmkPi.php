<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CpmkPi extends Model
{
    use HasFactory;

    protected $table = 'trx_cpmk_pi';

    protected $fillable = [
        'cpmk_id',
        'pi_id',
        'bobot',
    ];

    public function pi(): BelongsTo
    {
        return $this->belongsTo(Pi::class);
    }
}
