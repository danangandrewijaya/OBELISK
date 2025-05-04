<?php

use App\Models\Siklus;
use App\Models\SiklusCpl;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create((new Siklus())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->foreignId('kurikulum_id')->constrained('mst_kurikulum')->onDelete('cascade');
            $table->year('tahun_mulai');
            $table->year('tahun_selesai');
            $table->timestamps();
        });

        Schema::create((new SiklusCpl())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->foreignId('siklus_id')->constrained((new Siklus())->getTable())->onDelete('cascade');
            $table->foreignId('cpl_id')->constrained('mst_cpl')->onDelete('cascade');
            $table->foreignId('mata_kuliah_kurikulum_id')->constrained('mst_mata_kuliah_kurikulum')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['siklus_id', 'cpl_id', 'mata_kuliah_kurikulum_id'], 'unique_siklus_cpl');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists((new Siklus())->getTable());
        Schema::dropIfExists((new SiklusCpl())->getTable());
    }
};
