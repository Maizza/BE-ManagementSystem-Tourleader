<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskQuestion extends Model
{
    protected $fillable = ['task_id','order_no','question_text'];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}