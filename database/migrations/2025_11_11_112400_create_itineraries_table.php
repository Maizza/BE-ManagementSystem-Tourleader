<?php
// database/migrations/2025_11_11_000001_create_itineraries_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('itineraries', function (Blueprint $t) {
        $t->id();
        $t->string('title');
        $t->date('start_date')->nullable();
        $t->date('end_date')->nullable();
        $t->timestamps();
    });

    }
    public function down(): void { Schema::dropIfExists('itineraries'); }
};
