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
        Schema::create('krss', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas');
            $table->foreignId('matakuliah_id')->constrained('matakuliahs');
            $table->integer('semester')->unsigned();
            // $table->foreignId('kelas_id')->nullable()->constrained('kelas');
            $table->enum('verifikasi', ['y', 'n'])->default('n'); // y = sudah diverifikasi, n = belum diverifikasi
            // kolom unik mahasiswa_id + matakuliah_id + semester
            $table->unique(['mahasiswa_id', 'matakuliah_id', 'semester'], 'krs_unique');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('krss');
    }
};
