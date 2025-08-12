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
        Schema::create('kurikulums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prodi_id')->constrained('prodis');
            $table->uuid('feeder_id')->unique()->nullable();
            $table->string('nama');
            $table->string('keterangan')->nullable(); // keterangan tambahan untuk kurikulum
            $table->integer('semester')->unsigned()->nullable(); // Semester mulai berlaku
            $table->smallInteger('jumlah_sks_lulus')->unsigned()->nullable();
            $table->smallInteger('jumlah_sks_wajib')->unsigned()->nullable();
            $table->smallInteger('jumlah_sks_pilihan')->unsigned()->nullable();
            $table->smallInteger('jumlah_sks_mata_kuliah_wajib')->unsigned()->nullable();
            $table->smallInteger('jumlah_sks_mata_kuliah_pilihan')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kurikulums');
    }
};
