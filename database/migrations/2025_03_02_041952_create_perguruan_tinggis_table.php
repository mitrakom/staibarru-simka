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
        Schema::create('perguruan_tinggis', function (Blueprint $table) {
            $table->id();
            $table->uuid('feeder_id')->unique();
            $table->string('kode', 8)->nullable();
            $table->string('nama', 80)->nullable();
            $table->string('telepon', 20)->nullable();
            $table->string('faximile', 20)->nullable();
            $table->string('email', 80)->nullable();
            $table->string('website', 256)->nullable();
            $table->string('jalan', 80)->nullable();
            $table->string('dusun', 60)->nullable();
            $table->string('rt_rw', 7)->nullable();
            $table->string('kelurahan', 60)->nullable();
            $table->string('kode_pos', 5)->nullable();
            $table->string('id_wilayah', 8)->nullable();
            $table->string('nama_wilayah', 60)->nullable();
            $table->string('lintang_bujur', 24)->nullable();
            $table->string('bank', 50)->nullable();
            $table->string('unit_cabang', 60)->nullable();
            $table->string('nomor_rekening', 20)->nullable();
            $table->decimal('mbs', 1, 0)->nullable();
            $table->decimal('luas_tanah_milik', 7, 0)->nullable();
            $table->decimal('luas_tanah_bukan_milik', 7, 0)->nullable();
            $table->string('sk_pendirian', 80)->nullable();
            $table->date('tanggal_sk_pendirian')->nullable();
            $table->decimal('id_status_milik', 1, 0)->nullable();
            $table->string('nama_status_milik', 50)->nullable();
            $table->char('status_perguruan_tinggi', 1)->nullable();
            $table->string('sk_izin_operasional', 80)->nullable();
            $table->date('tanggal_izin_operasional')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perguruan_tinggis');
    }
};
