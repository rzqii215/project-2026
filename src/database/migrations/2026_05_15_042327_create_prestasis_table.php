<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prestasis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('mahasiswa_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('kategori_prestasi_id')
                ->constrained('kategori_prestasis')
                ->restrictOnDelete();

            $table->string('judul');
            $table->string('penyelenggara');
            $table->string('tingkat');
            $table->string('peringkat')->nullable();
            $table->date('tanggal_prestasi');
            $table->text('deskripsi')->nullable();

            $table->string('status')->default('diajukan');
            $table->text('catatan_verifikasi')->nullable();

            $table->foreignId('diverifikasi_oleh')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('diverifikasi_pada')->nullable();

            $table->timestamps();

            $table->index(['mahasiswa_id', 'status']);
            $table->index(['kategori_prestasi_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prestasis');
    }
};