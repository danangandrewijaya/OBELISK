<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CpmkCpl extends Model
{
    use HasFactory;

    protected $table = 'trx_cpmk_cpl';
    protected $fillable = ['cpmk_id', 'cpl_id', 'bobot'];

    public function cpmk()
    {
        return $this->belongsTo(Cpmk::class);
    }

    public function cpl()
    {
        return $this->belongsTo(Cpl::class);
    }
}
