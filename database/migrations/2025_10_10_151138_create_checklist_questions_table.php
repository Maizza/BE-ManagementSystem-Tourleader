<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('checklist_questions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('checklist_task_id')->constrained()->cascadeOnDelete();
            $t->unsignedInteger('order_no')->default(1);
            $t->text('question_text');
            $t->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('checklist_questions');
    }
};
