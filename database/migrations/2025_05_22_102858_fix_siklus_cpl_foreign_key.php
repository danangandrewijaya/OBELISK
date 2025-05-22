<?php

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
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Drop the table and recreate it with the correct foreign key constraint
        Schema::dropIfExists('trx_siklus_cpl');

        Schema::create('trx_siklus_cpl', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siklus_id')->constrained('trx_siklus')->onDelete('cascade'); // Fixed reference to trx_siklus
            $table->foreignId('cpl_id')->constrained('mst_cpl')->onDelete('cascade');
            $table->foreignId('mata_kuliah_semester_id')->constrained('mst_mata_kuliah_semester')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['siklus_id', 'cpl_id', 'mata_kuliah_semester_id'], 'unique_siklus_cpl_mks');
        });

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a fix migration, so we don't need to implement a true rollback
        // as it would reintroduce the bug
    }
};
