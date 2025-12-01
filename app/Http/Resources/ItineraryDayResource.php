<?php
// app/Http/Resources/ItineraryDayResource.php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItineraryDayResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'day_number' => $this->day_number,
            'city'       => $this->city,
            'date'       => $this->date,
            'items'      => $this->items, // boleh dibikin resource lagi kalau mau
        ];
    }
}
