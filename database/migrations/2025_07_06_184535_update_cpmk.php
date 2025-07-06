<?php

use App\Models\Cpmk;
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
        Schema::table((new Cpmk())->getTable(), function (Blueprint $table) {
            $table->text('pelaksanaan')->nullable()->after('level_taksonomi');
            $table->text('evaluasi')->nullable()->after('pelaksanaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table((new Cpmk())->getTable(), function (Blueprint $table) {
            $table->dropColumn('pelaksanaan');
            $table->dropColumn('evaluasi');
        });
    }
};
