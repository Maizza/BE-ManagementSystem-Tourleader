<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('kloters', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('tanggal'); // contoh: "13-20 September 2025"
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('kloters');
    }
};
