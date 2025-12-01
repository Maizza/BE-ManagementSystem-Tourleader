<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'checklist_task_id',
        'order_no',
        'question_text',
    ];

    public function task()
    {
        return $this->belongsTo(ChecklistTask::class, 'checklist_task_id');
    }
}
