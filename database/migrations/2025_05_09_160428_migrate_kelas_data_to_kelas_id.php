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
     */
    public function up(): void
    {
        // Step 1: Group all nilai records by mks_id and kelas to find unique combinations
        $uniqueKelasGroups = Nilai::select('mks_id', 'kelas')
            ->whereNotNull('kelas')
            ->where('kelas', '!=', '')
            ->distinct()
            ->get()
            ->groupBy('mks_id');

        // Step 2: For each unique mks_id and kelas combination, create a new kelas record
        foreach ($uniqueKelasGroups as $mksId => $kelasGroup) {
            $mks = MataKuliahSemester::find($mksId);

            if (!$mks) {
                Log::warning("MataKuliahSemester with ID {$mksId} not found");
                continue;
            }

            foreach ($kelasGroup as $kelasData) {
                $kelasName = $kelasData->kelas;

                // Create the kelas record if it doesn't exist yet
                $kelas = Kelas::firstOrCreate(
                    ['mks_id' => $mksId, 'nama_kelas' => $kelasName],
                    ['deskripsi' => "Kelas {$kelasName} untuk " . ($mks->mkk->nama ?? 'mata kuliah')]
                );

                // Update all nilai records with this mks_id and kelas to use the new kelas_id
                DB::table((new Nilai())->getTable())
                    ->where('mks_id', $mksId)
                    ->where('kelas', $kelasName)
                    ->update(['kelas_id' => $kelas->id]);

                Log::info("Updated nilai records for MKS ID {$mksId}, Kelas {$kelasName} to use Kelas ID {$kelas->id}");
            }
        }
    }

    /**
     * Reverse the migrations.
     * Since we're not removing any data, we don't need to do anything on rollback
     */
    public function down(): void
    {
        // No need to do anything on rollback as we're just adding data,
        // not removing any existing data
    }
};
