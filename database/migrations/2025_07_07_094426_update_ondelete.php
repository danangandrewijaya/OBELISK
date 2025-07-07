<?php

use App\Models\Dosen;
use App\Models\MataKuliahSemester;
use App\Models\Pengampu;
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
        Schema::table((new MataKuliahSemester())->getTable(), function (Blueprint $table) {
            $table->dropForeign(['koord_pengampu_id']);
            $table->foreign('koord_pengampu_id')->references('id')->on((new Dosen())->getTable())->onDelete('restrict');
            $table->dropForeign(['gpm_id']);
            $table->foreign('gpm_id')->references('id')->on((new Dosen())->getTable())->onDelete('restrict');
        });
        Schema::table((new Pengampu())->getTable(), function (Blueprint $table) {
            $table->dropForeign(['dosen_id']);
            $table->foreign('dosen_id')->references('id')->on((new Dosen())->getTable())->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table((new Pengampu())->getTable(), function (Blueprint $table) {
            $table->dropForeign(['dosen_id']);
            $table->foreign('dosen_id')->references('id')->on((new Dosen())->getTable())->onDelete('cascade');
        });
        Schema::table((new MataKuliahSemester())->getTable(), function (Blueprint $table) {
            $table->dropForeign(['koord_pengampu_id']);
            $table->foreign('koord_pengampu_id')->references('id')->on((new Dosen())->getTable())->onDelete('cascade');
            $table->dropForeign(['gpm_id']);
            $table->foreign('gpm_id')->references('id')->on((new Dosen())->getTable())->onDelete('cascade');
        });
    }
};
