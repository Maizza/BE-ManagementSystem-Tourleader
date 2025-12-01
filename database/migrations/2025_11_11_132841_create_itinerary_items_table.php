<?php
// database/migrations/2025_11_11_000003_create_itinerary_items_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('itinerary_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('itinerary_day_id')->constrained()->cascadeOnDelete();
            $t->unsignedInteger('sequence')->default(1); // urutan isi ke-1, ke-2
            $t->time('time')->nullable();  // jam
            $t->string('title')->nullable(); // opsional: judul singkat
            $t->text('content')->nullable(); // deskripsi kegiatan
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('itinerary_items'); }
};