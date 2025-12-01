<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tour_leaders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            
            // Token FCM utk push notif
            $table->string('fcm_token')->nullable();

            // Relasi ke tabel kloters
            $table->unsignedBigInteger('kloter_id')->nullable();
            $table->foreign('kloter_id')
            ->references('id')->on('kloters')
            ->onDelete('set null'); // lebih aman, biar tour leader gak ikut hilang

            $table->rememberToken();
            $table->timestamps();

            // Index tambahan opsional
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_leaders');
    }
};
