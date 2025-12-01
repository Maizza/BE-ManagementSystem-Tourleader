<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('checklist_answers', function (Blueprint $t) {
            $t->id();

            $t->foreignId('checklist_submission_id')
                ->constrained('checklist_submissions')
                ->cascadeOnDelete();

            $t->foreignId('checklist_question_id')
                ->constrained('checklist_questions')
                ->cascadeOnDelete();

            $t->enum('value', ['sudah', 'tidak', 'rekan']);
            $t->string('note')->nullable();
            $t->timestamps();

            $t->unique(
                ['checklist_submission_id', 'checklist_question_id'],
                'answers_sub_q_unique'
            );
        });
    }

    public function down(): void {
        Schema::dropIfExists('checklist_answers');
    }
};
