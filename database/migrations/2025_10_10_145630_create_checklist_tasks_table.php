<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('checklist_tasks', function (Blueprint $t) {
        $t->id();
        $t->string('title');
        $t->unsignedInteger('question_count')->nullable();
        $t->dateTime('opens_at');
        $t->dateTime('closes_at');
        $t->boolean('send_to_all')->default(false); // sesuai model
        $t->timestamps();
    });

    }
    public function down(): void {
        Schema::dropIfExists('checklist_tasks');
    }
};
