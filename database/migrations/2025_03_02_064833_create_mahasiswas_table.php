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
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prodi_id')->constrained('prodis');
            // $table->foreignId('agama_id')->constrained('ref_agamas');
            $table->enum('agama', ['I', 'P', 'K', 'H', 'B', 'C'])->nullable(); // I = Islam, P = Protestan, K = Katolik, H = Hindu, B = Budha, C = Khonghucu 
            $table->foreignId('kurikulum_id')->nullable()->constrained('kurikulums');
            $table->string('nim');
            $table->string('nama');
            $table->integer('angkatan');
            $table->string('email');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('handphone');
            $table->string('alamat');
            $table->string('nisn');
            $table->string('nik');
            $table->string('npwp');
            $table->enum('jenis_kelamin', ['L', 'P', '*']); // L = Laki-laki, P = Perempuan, * = Belum ada informasi
            $table->uuid('feeder_id')->nullable()->unique();  // kolom id_registrasi_mahasiswa pada endpoint Feeder (GetListMahasiswa)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};
