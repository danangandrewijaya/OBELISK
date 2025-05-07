<?php

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
        Schema::table('trx_nilai', function (Blueprint $table) {
            // Modify the semester column to allow NULL values
            $table->string('semester')->nullable()->change();

            // Modify the status column to allow NULL values
            $table->string('status')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trx_nilai', function (Blueprint $table) {
            // Revert the columns back to NOT NULL
            $table->string('semester')->nullable(false)->change();
            $table->string('status')->nullable(false)->change();
        });
    }
};
