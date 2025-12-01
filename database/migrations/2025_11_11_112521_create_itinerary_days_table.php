<?php
// database/migrations/2025_11_11_000002_create_itinerary_days_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('itinerary_days', function (Blueprint $t) {
            $t->id();
            $t->foreignId('itinerary_id')->constrained()->cascadeOnDelete();
            $t->unsignedInteger('day_number'); // 1,2,3,...
            $t->string('city')->nullable();
            $t->date('date')->nullable();
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('itinerary_days'); }
};