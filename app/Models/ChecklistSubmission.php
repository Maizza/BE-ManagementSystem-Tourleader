<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'checklist_task_id',
        'tourleader_id',
        'nama_petugas',
        'kloter',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function task()
    {
        return $this->belongsTo(ChecklistTask::class, 'checklist_task_id');
    }

    public function tourleader()
    {
        return $this->belongsTo(Tourleader::class, 'tourleader_id');
    }

    public function answers()
    {
        return $this->hasMany(ChecklistAnswer::class, 'checklist_submission_id');
    }
}
