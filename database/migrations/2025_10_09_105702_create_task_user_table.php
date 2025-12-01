<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('task_user', function (Illuminate\Database\Schema\Blueprint $t) {
        $t->id();

        // tasks.id = BIGINT UNSIGNED (dari $table->id())
        $t->foreignIdFor(\App\Models\Task::class) // membuat kolom task_id BIGINT
          ->constrained()                         // references tasks(id)
          ->cascadeOnDelete();

        // tour_leaders.id = BIGINT UNSIGNED
        $t->unsignedBigInteger('tourleader_id');
        $t->foreign('tourleader_id')
          ->references('id')
          ->on('tour_leaders')                    // <— pastikan nama tabel ini
          ->cascadeOnDelete();

        $t->timestamp('done_at')->nullable();
        $t->timestamps();

        $t->unique(['task_id','tourleader_id']);
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_user');
    }
};
