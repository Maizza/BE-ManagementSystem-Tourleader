<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_jamaah', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel jamaahs
            $table->foreignId('jamaah_id')
                  ->constrained('jamaahs')
                  ->onDelete('cascade');

            // Data absensi
            $table->date('tanggal')->index();
            $table->string('sesi'); // contoh: "Briefing Bandara", "Shalat Subuh", dsb
            $table->enum('status', ['HADIR', 'TIDAK_HADIR']);

            // Opsional
            $table->text('catatan')->nullable();

            // TL / petugas absensi
            $table->foreignId('created_by')
                  ->nullable()
                  ->comment('Tour Leader yang meng-input absensi')
                  ->constrained('users')
                  ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_jamaah');
    }
};