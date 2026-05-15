<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berkas_prestasis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('prestasi_id')
                ->constrained('prestasis')
                ->cascadeOnDelete();

            $table->string('nama_file');
            $table->string('path_file');
            $table->string('tipe_file')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berkas_prestasis');
    }
};