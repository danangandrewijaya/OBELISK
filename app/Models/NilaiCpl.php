<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NilaiCpl extends Model
{
    protected $table = 'trx_nilai_cpl';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function nilai(): BelongsTo
    {
        return $this->belongsTo(Nilai::class);
    }

    public function cpl(): BelongsTo
    {
        return $this->belongsTo(Cpl::class);
    }

    public function createNilaiCplFromMks($mks)
    {
        // Get all nilai for this MKS
        $nilaiList = Nilai::where('mks_id', $mks->id)->get();

        // Get all CPMKs and their CPL relationships for this MKS
        $cpmks = $mks->cpmk()->with('cpmkCpl')->get();

        foreach ($nilaiList as $nilai) {
            // Get CPMK scores for this nilai
            $nilaiCpmks = NilaiCpmk::where('nilai_id', $nilai->id)->get();

            // Group CPMK scores by CPL and calculate weighted average
            $cplScores = [];

            foreach ($nilaiCpmks as $nilaiCpmk) {
                // Get all CPLs related to this CPMK
                $cpmkCpls = CpmkCpl::where('cpmk_id', $nilaiCpmk->cpmk_id)->get();

                foreach ($cpmkCpls as $cpmkCpl) {
                    if (!isset($cplScores[$cpmkCpl->cpl_id])) {
                        $cplScores[$cpmkCpl->cpl_id] = [
                            'total_score' => 0,
                            'total_weight' => 0
                        ];
                    }

                    // Add weighted score to CPL
                    $cplScores[$cpmkCpl->cpl_id]['total_score'] += $nilaiCpmk->nilai_angka * $cpmkCpl->bobot;
                    $cplScores[$cpmkCpl->cpl_id]['total_weight'] += $cpmkCpl->bobot;
                }
            }

            // Create/update NilaiCpl entries
            foreach ($cplScores as $cplId => $scores) {
                if ($scores['total_weight'] > 0) {
                    $nilaiAngka = $scores['total_score'] / $scores['total_weight'];
                    $nilaiBobot = $nilaiAngka / 25; // Convert 0-100 to 0-4 scale

                    NilaiCpl::updateOrCreate(
                        [
                            'nilai_id' => $nilai->id,
                            'cpl_id' => $cplId,
                        ],
                        [
                            'nilai_angka' => round($nilaiAngka, 2),
                            'nilai_bobot' => round($nilaiBobot, 2),
                        ]
                    );
                }
            }
        }
    }
}
