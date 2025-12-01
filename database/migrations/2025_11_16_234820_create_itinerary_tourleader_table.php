<?php
// database/migrations/2025_11_XX_XXXXXX_create_itinerary_tour_leader_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('itinerary_tour_leader', function (Blueprint $table) {
            $table->id();
            $table->foreignId('itinerary_id')->constrained('itineraries')->cascadeOnDelete();
            $table->foreignId('tour_leader_id')->constrained('tour_leaders')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['itinerary_id', 'tour_leader_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('itinerary_tour_leader');
    }
};
