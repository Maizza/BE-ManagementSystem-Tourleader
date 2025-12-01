<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title','question_count','opens_at','closes_at'];

    protected $casts = [
        'opens_at'  => 'datetime',
        'closes_at' => 'datetime',
    ];

    public function questions()
    {
        return $this->hasMany(TaskQuestion::class)->orderBy('order_no');
    }

    public function tourleaders()
    {
        // sebutkan nama model & kolom pivot secara eksplisit
        return $this->belongsToMany(TourLeader::class, 'task_user', 'task_id', 'tourleader_id')
                    ->withPivot('done_at')
                    ->withTimestamps();
    }

    // accessor status sekarang
    public function getStatusAttribute(): string
    {
        $now = now();
        if ($now->lt($this->opens_at)) return 'belum_dibuka';
        if ($now->gt($this->closes_at)) return 'ditutup';
        return 'dibuka';
    }

    public function doneTourleaders()
    {
        return $this->tourleaders()->whereNotNull('task_user.done_at');
    }

    public function notDoneTourleaders()
    {
        return $this->tourleaders()->whereNull('task_user.done_at');
    }
}
