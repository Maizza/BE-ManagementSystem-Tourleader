<?php
// app/Models/ItineraryDay.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItineraryDay extends Model
{
    protected $fillable = ['itinerary_id','day_number','city','date'];
    protected $casts = ['date'=>'date'];

    public function itinerary(): BelongsTo { return $this->belongsTo(Itinerary::class); }
    public function items(): HasMany { return $this->hasMany(ItineraryItem::class)->orderBy('sequence'); }
}
