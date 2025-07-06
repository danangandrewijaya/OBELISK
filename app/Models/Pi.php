<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pi extends Model
{
    use HasFactory;

    protected $table = 'mst_pi';

    protected $fillable = [
        'nomor',
        'deskripsi',
        'cpl_id',
    ];

    public function cpl()
    {
        return $this->belongsTo(Cpl::class);
    }
}
