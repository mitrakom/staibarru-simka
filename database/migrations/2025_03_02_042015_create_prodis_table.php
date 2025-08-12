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
        Schema::create('prodis', function (Blueprint $table) {

            // {
            //     "id_prodi": "e56917ec-ad3e-49bf-b474-40e46c61f5dd",
            //     "kode_program_studi": "70201",
            //     "nama_program_studi": "Ilmu Komunikasi",
            //     "status": "A",
            //     "id_jenjang_pendidikan": "30",
            //     "nama_jenjang_pendidikan": "S1"
            // },

            $table->id();
            $table->foreignId('perguruan_tinggi_id')->constrained('perguruan_tinggis');
            $table->foreignId('fakultas_id')->nullable()->constrained('fakultas');
            $table->uuid('feeder_id')->unique();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->char('status', 1)->nullable();
            $table->smallInteger('jenjang_pendidikan_id')->nullable();
            $table->string('telepon')->nullable();
            $table->string('kaprodi_nama')->nullable();
            $table->string('kaprodi_nidn')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prodis');
    }
};
