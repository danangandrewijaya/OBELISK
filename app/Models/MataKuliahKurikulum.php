<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kurikulum;

class MataKuliahKurikulum extends Model
{
    use HasFactory;

    protected $table = 'mst_mata_kuliah_kurikulum';

    protected $fillable = [
        'kode',
        'nama',
        'kurikulum_id',
        'sks',
    ];
    
    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class);
    }
}
