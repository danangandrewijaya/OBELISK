<?php

use App\Models\Cpl;
use App\Models\MataKuliahKurikulum;
use App\Models\MataKuliahSemester;
use App\Models\SiklusCpl;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get existing data before dropping the table
        $existingData = DB::table((new SiklusCpl())->getTable())->get();
        $mappedData = [];

        // Create a mapping of mata_kuliah_kurikulum_id to mata_kuliah_semester_id
        // This is a simplification - in a real application you would need a more complex mapping logic
        // based on your specific business rules
        foreach ($existingData as $record) {
            if (isset($record->mata_kuliah_kurikulum_id)) {
                // Find a corresponding mata kuliah semester (we'll use a placeholder for now)
                // In a real application, you would need to look up the proper MKS for each MKK
                $mksId = DB::table((new MataKuliahSemester())->getTable())
                    ->where('mkk_id', $record->mata_kuliah_kurikulum_id)
                    ->value('id');

                if ($mksId) {
                    $mappedData[] = [
                        'siklus_id' => $record->siklus_id,
                        'cpl_id' => $record->cpl_id,
                        'mata_kuliah_semester_id' => $mksId
                    ];
                }
            }
        }

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Drop and recreate the table
        Schema::dropIfExists((new SiklusCpl())->getTable());

        Schema::create((new SiklusCpl())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->foreignId('siklus_id')->constrained((new SiklusCpl())->getTable())->onDelete('cascade');
            $table->foreignId('cpl_id')->constrained((new Cpl())->getTable())->onDelete('cascade');
            $table->foreignId('mata_kuliah_semester_id')->constrained((new MataKuliahSemester())->getTable())->onDelete('cascade');
            $table->timestamps();

            $table->unique(['siklus_id', 'cpl_id', 'mata_kuliah_semester_id'], 'unique_siklus_cpl_mks');
        });

        // Reinsert the data with the new structure
        if (!empty($mappedData)) {
            DB::table((new SiklusCpl())->getTable())->insert($mappedData);
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get existing data before dropping the table
        $existingData = DB::table((new SiklusCpl())->getTable())->get();
        $mappedData = [];

        // Create a mapping of mata_kuliah_semester_id to mata_kuliah_kurikulum_id
        foreach ($existingData as $record) {
            if (isset($record->mata_kuliah_semester_id)) {
                // Find the corresponding MKK for each MKS
                $mkkId = DB::table((new MataKuliahSemester())->getTable())
                    ->where('id', $record->mata_kuliah_semester_id)
                    ->value('mkk_id');

                if ($mkkId) {
                    $mappedData[] = [
                        'siklus_id' => $record->siklus_id,
                        'cpl_id' => $record->cpl_id,
                        'mata_kuliah_kurikulum_id' => $mkkId
                    ];
                }
            }
        }

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Drop and recreate the table with the old structure
        Schema::dropIfExists((new SiklusCpl())->getTable());

        Schema::create((new SiklusCpl())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->foreignId('siklus_id')->constrained((new SiklusCpl())->getTable())->onDelete('cascade');
            $table->foreignId('cpl_id')->constrained((new Cpl())->getTable())->onDelete('cascade');
            $table->foreignId('mata_kuliah_kurikulum_id')->constrained((new MataKuliahKurikulum())->getTable())->onDelete('cascade');
            $table->timestamps();

            $table->unique(['siklus_id', 'cpl_id', 'mata_kuliah_kurikulum_id'], 'unique_siklus_cpl');
        });

        // Reinsert the data with the old structure
        if (!empty($mappedData)) {
            DB::table((new SiklusCpl())->getTable())->insert($mappedData);
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
