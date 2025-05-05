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
        Schema::create('import_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action', 50); // submit, preview, confirm, cancel
            $table->string('file_name')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_type')->nullable();
            $table->string('file_size')->nullable();
            $table->string('status', 20)->default('success'); // success, failed, pending
            $table->text('details')->nullable(); // JSON data with additional details
            $table->string('error_message')->nullable();
            $table->unsignedBigInteger('mks_id')->nullable(); // Mata Kuliah Semester ID if applicable
            $table->timestamps();

            // Foreign key constraints - commented out initially, uncomment after confirming table structures
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            // $table->foreign('mks_id')->references('id')->on('mata_kuliah_semesters')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_logs');
    }
};
