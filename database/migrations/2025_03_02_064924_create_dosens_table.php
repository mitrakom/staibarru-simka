<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    //  contoh respon migrasi data feeder


    public function up(): void
    {
        Schema::create('dosens', function (Blueprint $table) {
            $table->id();
            $table->uuid('feeder_id')->unique()->nullable();
            $table->string('nama', 100);
            $table->string('tempat_lahir', 32)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            // $table->string('gelar_depan')->nullable();
            $table->unsignedSmallInteger('agama_id')->nullable();
            // $table->string('nama_agama')->nullable();
            $table->unsignedInteger('status_aktif_id')->nullable();
            // $table->string('nama_status_aktif')->nullable();
            $table->string('nidn', 10)->nullable();
            $table->string('nuptk', 35)->nullable();
            $table->string('nama_ibu_kandung', 100)->nullable();
            $table->string('nik', 20)->nullable();
            $table->string('nip', 18)->nullable();
            $table->string('npwp', 15)->nullable();
            $table->unsignedInteger('jenis_sdm_id')->nullable();
            // $table->string('nama_jenis_sdm')->nullable();
            $table->string('no_sk_cpns', 80)->nullable();
            $table->date('tanggal_sk_cpns')->nullable();
            $table->string('no_sk_pengangkatan', 80)->nullable();
            $table->date('mulai_sk_pengangkatan')->nullable();
            $table->unsignedInteger('lembaga_pengangkatan_id')->nullable();
            // $table->string('nama_lembaga_pengangkatan')->nullable();
            $table->unsignedInteger('pangkat_golongan_id')->nullable();
            // $table->string('nama_pangkat_golongan')->nullable();
            $table->unsignedInteger('sumber_gaji_id')->nullable();
            // $table->string('nama_sumber_gaji')->nullable();
            $table->string('jalan')->nullable();
            $table->string('dusun', 60)->nullable();
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();
            $table->string('ds_kel', 60)->nullable();
            $table->string('kode_pos', 5)->nullable();
            $table->string('wilayah_id', 8)->nullable();
            // $table->string('nama_wilayah')->nullable();
            $table->string('telepon', 20)->nullable();
            $table->string('handphone', 20)->nullable();
            $table->string('email', 60)->nullable();
            $table->unsignedInteger('status_pernikahan')->nullable();
            $table->string('nama_suami_istri', 100)->nullable();
            $table->string('nip_suami_istri', 18)->nullable();
            $table->date('tanggal_mulai_pns')->nullable();
            $table->string('pekerjaan_suami_istri_id')->nullable();
            // $table->string('nama_pekerjaan_suami_istri')->nullable();

            // Kolom Sinkronisasi Feeder
            // $table->enum('feeder_status_sync', ['baru', 'sudah_sync', 'gagal', 'diperbarui'])->default('baru');
            // $table->dateTime('feeder_tanggal_sync')->nullable();
            // $table->dateTime('feeder_last_update')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosens');
    }
};
