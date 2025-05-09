<?php

use App\Models\Kelas;
use App\Models\MataKuliahSemester;
use App\Models\Nilai;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Create "TANPA NAMA" classes for nilai records with empty kelas values
     */
    public function up(): void
    {
        // Step 1: Find all nilai records with empty kelas values
        $nilaiWithEmptyKelas = Nilai::whereNull('kelas')
            ->orWhere('kelas', '')
            ->select('mks_id')
            ->distinct()
            ->get();

        // Step 2: For each unique mks_id, create a default "TANPA NAMA" kelas
        foreach ($nilaiWithEmptyKelas as $nilai) {
            $mksId = $nilai->mks_id;

            $mks = MataKuliahSemester::find($mksId);
            if (!$mks) {
                Log::warning("MataKuliahSemester with ID {$mksId} not found");
                continue;
            }

            // Create a default "TANPA NAMA" kelas for this mks_id if it doesn't exist
            $kelas = Kelas::firstOrCreate(
                ['mks_id' => $mksId, 'nama_kelas' => 'TANPA NAMA'],
                ['deskripsi' => 'Kelas default untuk ' . ($mks->mkk->nama ?? 'mata kuliah')]
            );

            // Update all nilai records with empty kelas for this mks_id
            DB::table((new Nilai())->getTable())
                ->where('mks_id', $mksId)
                ->where(function($query) {
                    $query->whereNull('kelas')
                        ->orWhere('kelas', '');
                })
                ->update([
                    'kelas' => 'TANPA NAMA',
                    'kelas_id' => $kelas->id
                ]);

            Log::info("Updated nilai records with empty kelas for MKS ID {$mksId} to use Kelas ID {$kelas->id}");
        }
    }

    /**
     * Reverse the migrations.
     * Since we're not removing data, there's nothing specific to undo
     */
    public function down(): void
    {
        // No specific action needed for rollback since we're adding data
    }
};
