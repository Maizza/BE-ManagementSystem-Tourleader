<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Scan;
use App\Models\Kloter;
use App\Models\Task;

use Laravel\Sanctum\HasApiTokens;

class TourLeader extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'tour_leaders';

    protected $fillable = ['name', 'email', 'password', 'fcm_token', 'kloter_id'];
    protected $hidden = ['password', 'remember_token'];

    public function scans()
    {
        return $this->hasMany(Scan::class, 'tourleader_id');
    }

    public function kloter()
    {
        return $this->belongsTo(Kloter::class, 'kloter_id');
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_user', 'tourleader_id', 'task_id')
                    ->withPivot('done_at')
                    ->withTimestamps();
    }

    public function checklistTasks()
    {
        return $this->belongsToMany(\App\Models\ChecklistTask::class, 'checklist_task_user', 'tourleader_id', 'checklist_task_id')
                    ->withPivot('done_at')
                    ->withTimestamps();
    }

    public function itineraries()
    {
        return $this->belongsToMany(
            \App\Models\Itinerary::class,
            'itinerary_tour_leader',
            'tour_leader_id',
            'itinerary_id'
        )->withTimestamps();
    }
}
