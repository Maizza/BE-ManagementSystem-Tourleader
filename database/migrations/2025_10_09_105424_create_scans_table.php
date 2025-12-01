<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('scans', function (Blueprint $table) {
        $table->id();
        $table->string('koper_code');
        $table->string('owner_name')->nullable();
        $table->string('owner_phone', 30)->nullable();

        // UBAH INI: tambahkan ->nullable()
        $table->foreignId('tourleader_id')
              ->nullable()  // ← TAMBAHKAN INI
              ->constrained('tour_leaders')
              ->cascadeOnDelete();

        $table->string('kloter')->nullable();
        $table->timestamp('scanned_at')->useCurrent();
        $table->timestamps();

        $table->index('koper_code');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scans');
    }
};
