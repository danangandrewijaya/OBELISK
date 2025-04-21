<?php

use App\Models\Dosen;
use App\Models\MataKuliahSemester;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Pengampu;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update Tabel Mata Kuliah Semester drop pengampu
        Schema::table((new MataKuliahSemester())->getTable(), function (Blueprint $table) {
            $table->dropForeign(['pengampu_id']);
            $table->dropColumn(['pengampu_id']);
        });

        // Tabel Pengampu
        Schema::create((new Pengampu)->getTable(), function (Blueprint $table) {
            $table->id();
            $table->foreignId('mks_id')->constrained((new MataKuliahSemester)->getTable())->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained((new Dosen())->getTable())->onDelete('cascade');
            $table->timestamps();

            $table->unique(['mks_id', 'dosen_id']);
        });

    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback Tabel Pengampu
        Schema::dropIfExists((new Pengampu)->getTable());

        // Update Tabel Mata Kuliah Semester add pengampu
        Schema::table((new MataKuliahSemester)->getTable(), function (Blueprint $table) {
            $table->foreignId('pengampu_id')->nullable()->constrained((new Pengampu)->getTable())->onDelete('cascade');
        });
    }
};
