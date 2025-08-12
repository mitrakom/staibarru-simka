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
        Schema::create('import_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->string('name')->unique();  // method name: 'perguruanTinggi', 'fakultas', 'prodi'
            $table->enum('status', ['pending', 'in_progress', 'done', 'failed'])->default('pending');
            $table->text('error_summary')->nullable();
            $table->text('result')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_statuses');
    }
};
