<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'checklist_submission_id',
        'checklist_question_id',
        'value',
        'note',
    ];

    public function submission()
    {
        return $this->belongsTo(ChecklistSubmission::class, 'checklist_submission_id');
    }

    public function question()
    {
        return $this->belongsTo(ChecklistQuestion::class, 'checklist_question_id');
    }
}
