<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Dosen;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, drop the unique index
        Schema::table((new Dosen)->getTable(), function (Blueprint $table) {
            // Try to drop the unique index - we'll use a try-catch in case it doesn't exist
            try {
                $table->dropUnique('mst_dosen_nidn_unique');
            } catch (\Exception $e) {
                // If the index doesn't exist, just continue
            }
        });

        // Then modify the column to be nullable
        Schema::table((new Dosen)->getTable(), function (Blueprint $table) {
            $table->string('nidn', 20)->nullable()->change();
            $table->string('nip', 30)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table((new Dosen)->getTable(), function (Blueprint $table) {
            $table->string('nidn', 20)->change();
            $table->string('nip', 20)->change();
        });

        Schema::table((new Dosen)->getTable(), function (Blueprint $table) {
            // Try to drop the unique index - we'll use a try-catch in case it doesn't exist
            try {
                $table->dropUnique('mst_dosen_nidn_unique');
            } catch (\Exception $e) {
                // If the index doesn't exist, just continue
            }
        });
    }
};
