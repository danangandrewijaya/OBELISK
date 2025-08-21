<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Prodi;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table(config('permission.table_names.model_has_roles'), function (Blueprint $table) {
            // add nullable prodi_id to the pivot
            $table->unsignedBigInteger('prodi_id')->nullable()->after('role_id');
            $table->foreign('prodi_id')->references('id')->on((new Prodi)->getTable())->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(config('permission.table_names.model_has_roles'), function (Blueprint $table) {
            $table->dropForeign([ 'prodi_id' ]);
            $table->dropColumn('prodi_id');
        });
    }
};
