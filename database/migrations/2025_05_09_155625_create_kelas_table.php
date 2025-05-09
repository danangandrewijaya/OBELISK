<?php

use App\Models\Kelas;
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
        Schema::create((new Kelas())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->foreignId('mks_id')->comment('Foreign key to matakuliah semester table')->constrained('mst_mata_kuliah_semester')->onDelete('cascade');
            $table->string('nama_kelas', 20);
            $table->string('deskripsi')->nullable();
            $table->integer('kapasitas')->default(40);
            $table->timestamps();

            $table->unique(['mks_id', 'nama_kelas']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists((new Kelas())->getTable());
    }
};
