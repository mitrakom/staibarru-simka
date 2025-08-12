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
        Schema::create('kurikulum_matakuliahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kurikulum_id')->constrained('kurikulums');
            $table->foreignId('matakuliah_id')->constrained('matakuliahs');
            $table->tinyInteger('semester_ke')->unsigned();
            $table->boolean('apakah_wajib')->nullable();  // 1: Wajib, 0: Pilihan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matakuliah_kurikulums');
    }
};
