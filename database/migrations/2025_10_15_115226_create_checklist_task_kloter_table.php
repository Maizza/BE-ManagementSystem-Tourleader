<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('checklist_task_kloter', function (Blueprint $t) {
            $t->id();
            $t->foreignId('checklist_task_id')->constrained()->cascadeOnDelete();
            $t->foreignId('kloter_id')->constrained()->cascadeOnDelete();
            $t->unique(['checklist_task_id', 'kloter_id']);
            $t->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('checklist_task_kloter');
    }
};
