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
        Schema::create('ref_agamas', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->timestamps();
        });

        Schema::create('ref_jenjangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama');
            // $table->string('status')->nullable();
            $table->timestamps();
        });
        Schema::create('ref_semesters', function (Blueprint $table) {
            $table->id();
            $table->integer('key')->unique();
            $table->string('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_semesters');
        Schema::dropIfExists('ref_jenjangs');
        Schema::dropIfExists('ref_agamas');
    }
};
