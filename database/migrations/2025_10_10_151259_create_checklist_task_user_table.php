<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('checklist_task_user', function (Blueprint $t) {
            $t->id();
            $t->foreignId('checklist_task_id')->constrained()->cascadeOnDelete();
           $t->foreignId('tourleader_id')->constrained('tour_leaders')->cascadeOnDelete();
            $t->timestamp('done_at')->nullable();
            $t->timestamps();
            $t->unique(['checklist_task_id','tourleader_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('checklist_task_user');
    }
};
