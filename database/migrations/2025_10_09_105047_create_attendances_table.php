<?php

// database/migrations/2025_01_01_000000_create_attendances_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('attendances', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('tour_leader_id');
      $table->string('name');              // simpan snapshot nama saat absen
      $table->string('kloter')->nullable();// snapshot kloter TL saat absen
      $table->string('photo_path');        // storage path
      $table->decimal('lat', 10, 7)->nullable();
      $table->decimal('lng', 10, 7)->nullable();
      $table->timestamps();
    
      $table->foreign('tour_leader_id')->references('id')->on('tour_leaders')->cascadeOnDelete();
      $table->index(['tour_leader_id','created_at']);
    });
  }
  public function down(): void {
    Schema::dropIfExists('attendances');
  }
};

