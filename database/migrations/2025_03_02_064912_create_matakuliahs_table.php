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
        Schema::create('matakuliahs', function (Blueprint $table) {
            $table->id();
            $table->uuid('feeder_id')->unique()->nullable();
            $table->foreignId('prodi_id')->constrained('prodis');
            $table->string('kode'); // MKP-101, MKK-101, MKB-101, MPB-101, MBB-101, MKU-101, MKDU-101, MKDK-101
            $table->string('nama');
            $table->tinyInteger('sks');
            $table->enum('jenis_matakuliah_id', ['A', 'B', 'C', 'D', 'S'])->nullable();  // A: Wajib, B: Pilihan, C: Wajib Peminatan, D: Pilihan Peminatan, S: Tugas Akhir, Skripsi, Thesis, Disertasi
            $table->enum('kelompok_matakuliah_id', ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'])->nullable();  // A: MKP, B: MKK, C: MKB, D: MPB, E: MBB, F: MKU/MKDU, G: MKDK, H: MKK;
            $table->tinyInteger('sks_tatap_muka')->nullable();
            $table->tinyInteger('sks_praktek')->nullable();
            $table->tinyInteger('sks_praktek_lapangan')->nullable();
            $table->tinyInteger('sks_simulasi')->nullable();
            $table->string('metode_kuliah')->nullable();
            $table->boolean('ada_sap')->nullable();
            $table->boolean('ada_silabus')->nullable();
            $table->boolean('ada_bahan_ajar')->nullable();
            $table->boolean('ada_acara_praktek')->nullable();
            $table->boolean('ada_diktat')->nullable();
            $table->date('tanggal_mulai_efektif')->nullable();
            $table->date('tanggal_selesai_efektif')->nullable();
            $table->boolean('apakah_wajib')->nullable();  // 1: Wajib, 0: Pilihan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matakuliahs');
    }
};
