<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Itinerary extends Model
{
    protected $fillable = ['title','start_date','end_date','tour_leader_name'];
    protected $casts = ['start_date'=>'date','end_date'=>'date'];

    public function days(): HasMany { return $this->hasMany(ItineraryDay::class); }

    // relation ke many tourleaders
    public function tourLeaders(): BelongsToMany
    {
        return $this->belongsToMany(
            \App\Models\TourLeader::class,
            'itinerary_tour_leader',
            'itinerary_id',
            'tour_leader_id'
        )->withTimestamps();
    }
}
