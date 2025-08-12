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
        Schema::create('pembayaran_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prodi_id')->constrained('prodis');
            $table->string('nama');
            $table->integer('angkatan');
            $table->decimal('jumlah', 15, 2);
            $table->timestamps();
        });

        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembayaran_item_id')->constrained('pembayaran_items');
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas');
            $table->string('keterangan')->nullable();
            $table->decimal('jumlah', 15, 2);
            $table->dateTime('tanggal_bayar')->nullable()->note('Tanggal Pembayaran');
            $table->string('bukti_bayar')->nullable()->note('Bukti pembayaran / foto bukti transfer');
            $table->unsignedInteger('semester');
            $table->enum('status', ['lunas', 'belum lunas', 'cicilan', 'tunggakan', 'belum verifikasi'])->default('belum verifikasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
        Schema::dropIfExists('pembayaran_items');
    }
};
