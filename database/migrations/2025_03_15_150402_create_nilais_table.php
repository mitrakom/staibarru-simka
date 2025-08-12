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
        Schema::create('nilais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('krs_id')->constrained('krss');
            $table->string('nilai');
            $table->string('huruf');
            $table->string('index');
            $table->string('bobot');
            $table->string('mutu');
            $table->enum('is_transfer', ['y', 't'])->default('t');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilais');
    }
};
