<?php

use App\Models\Siklus2;
use App\Models\SiklusPi;
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
        Schema::create((new Siklus2())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->foreignId('kurikulum_id')->constrained('mst_kurikulum')->onDelete('cascade');
            $table->year('tahun_mulai');
            $table->year('tahun_selesai');
            $table->timestamps();
        });

        Schema::create((new SiklusPi())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->foreignId('siklus2_id')->constrained((new Siklus2())->getTable())->onDelete('cascade');
            $table->foreignId('pi_id')->constrained('mst_pi')->onDelete('cascade');
            $table->foreignId('mata_kuliah_semester_id')->constrained('mst_mata_kuliah_semester')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['siklus2_id', 'pi_id', 'mata_kuliah_semester_id'], 'unique_siklus_pi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists((new SiklusPi())->getTable());
        Schema::dropIfExists((new Siklus2())->getTable());
    }
};
