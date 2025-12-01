<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('checklist_submissions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('checklist_task_id')->constrained()->cascadeOnDelete();
            $t->foreignId('tourleader_id')->constrained('tour_leaders')->cascadeOnDelete();
            $t->string('nama_petugas');
            $t->string('kloter');
            $t->timestamp('submitted_at')->nullable();
            $t->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('checklist_submissions');
    }
};
