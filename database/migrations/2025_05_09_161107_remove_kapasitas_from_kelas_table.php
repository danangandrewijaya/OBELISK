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
        Schema::table((new Kelas())->getTable(), function (Blueprint $table) {
            $table->dropColumn('kapasitas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table((new Kelas())->getTable(), function (Blueprint $table) {
            $table->integer('kapasitas')->default(40)->after('deskripsi');
        });
    }
};
