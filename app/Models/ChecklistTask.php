<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'question_count',
        'opens_at',
        'closes_at',
        'send_to_all',
    ];

    protected $casts = [
        'opens_at' => 'datetime',
        'closes_at' => 'datetime',
        'send_to_all' => 'boolean',
    ];

    public function questions()
    {
        return $this->hasMany(ChecklistQuestion::class)
                    ->orderBy('order_no', 'asc');
    }

    public function tourleaders()
    {
        return $this->belongsToMany(Tourleader::class, 'checklist_task_user', 'checklist_task_id', 'tourleader_id')
                    ->withPivot('done_at')
                    ->withTimestamps();
    }

    public function submissions()
    {
        return $this->hasMany(ChecklistSubmission::class);
    }

    public function kloters()
    {
        return $this->belongsToMany(Kloter::class, 'checklist_task_kloter', 'checklist_task_id', 'kloter_id');
    }

    public function getStatusAttribute(): string
    {
        $now = now();
        if ($now->lt($this->opens_at)) return 'belum_dibuka';
        if ($now->gt($this->closes_at)) return 'ditutup';
        return 'dibuka';
    }
}
