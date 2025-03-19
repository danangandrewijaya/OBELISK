<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CpmkPi extends Model
{
    use HasFactory;

    protected $table = 'trx_cpmk_pi';

    protected $fillable = [
        'cpmk_id',
        'pi_id',
        'bobot',
    ];
}
