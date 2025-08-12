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
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 35);  // nama kelas atau kode kelas
            $table->string('keterangan', 100)->nullable();
            $table->unsignedInteger('semester');
            $table->foreignId('prodi_id')->constrained('prodis');
            $table->foreignId('matakuliah_id')->constrained('matakuliahs');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
