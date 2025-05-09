<?php

use App\Models\Nilai;
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
        Schema::table((new Nilai())->getTable(), function (Blueprint $table) {
            $table->foreignId('kelas_id')->nullable()->after('kelas')->comment('Foreign key to kelas table')->constrained('mst_kelas')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table((new Nilai())->getTable(), function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
            $table->dropColumn('kelas_id');
        });
    }
};
