<?php 
// app/Models/ItineraryItem.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItineraryItem extends Model
{
    protected $fillable = ['itinerary_day_id','sequence','time','title','content'];

    // penting: biarkan time sebagai string mentah
    protected $casts = [
        'time' => 'string',
    ];

    // akses untuk input HTML5 time
    public function getTimeForInputAttribute()
    {
        if (empty($this->time)) return null;

        // handle "08:58:00", "08:58:00.000000", atau sudah "08:58"
        $t = $this->time;
        if (preg_match('/^\d{2}:\d{2}$/', $t)) {
            return $t; // sudah H:i
        }
        // ambil 5 karakter pertama -> H:i
        return substr($t, 0, 5);
    }
}
