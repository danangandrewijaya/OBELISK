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

    public function createNilaiPiFromMks($mks)
    {
        // Get all nilai for this MKS
        $nilaiList = Nilai::where('mks_id', $mks->id)->get();

        // Get all CPMKs and their PI relationships for this MKS
        $cpmks = $mks->cpmk()->with('cpmkPi')->get();

        foreach ($nilaiList as $nilai) {
            // Get CPMK scores for this nilai
            $nilaiCpmks = NilaiCpmk::where('nilai_id', $nilai->id)->get();

            // Group CPMK scores by PI and calculate weighted average
            $piScores = [];

            foreach ($nilaiCpmks as $nilaiCpmk) {
                // Get all PIs related to this CPMK
                $cpmkPis = CpmkPi::where('cpmk_id', $nilaiCpmk->cpmk_id)->get();

                foreach ($cpmkPis as $cpmkPi) {
                    if (!isset($piScores[$cpmkPi->pi_id])) {
                        $piScores[$cpmkPi->pi_id] = [
                            'total_score' => 0,
                            'total_weight' => 0
                        ];
                    }

                    // Add weighted score to PI
                    $piScores[$cpmkPi->pi_id]['total_score'] += $nilaiCpmk->nilai_angka * $cpmkPi->bobot;
                    $piScores[$cpmkPi->pi_id]['total_weight'] += $cpmkPi->bobot;
                }
            }

            // Create/update NilaiPi entries
            foreach ($piScores as $piId => $scores) {
                if ($scores['total_weight'] > 0) {
                    $nilaiAngka = $scores['total_score'] / $scores['total_weight'];
                    $nilaiBobot = $nilaiAngka / 25; // Convert 0-100 to 0-4 scale

                    NilaiPi::updateOrCreate(
                        [
                            'nilai_id' => $nilai->id,
                            'pi_id' => $piId,
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
