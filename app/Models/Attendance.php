<?php

// app/Models/Attendance.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['tour_leader_id','name','kloter','photo_path','lat','lng'];

    public function tourleader() {
        return $this->belongsTo(Tourleader::class, 'tour_leader_id');
    }
}

