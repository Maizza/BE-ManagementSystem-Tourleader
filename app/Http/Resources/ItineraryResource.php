<?php
// app/Http/Resources/ItineraryResource.php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\TourLeader;

class ItineraryResource extends JsonResource
{
    public function toArray($request)
    {
        // Cari user yang lagi login (coba default guard dulu, lalu guard 'tourleader')
        $auth = $request->user() ?? auth('tourleader')->user();

        $currentTlName = null;

        if ($auth instanceof TourLeader) {
            // Pastikan TL yang login memang terkait itinerary ini
            $match = $this->tourLeaders->firstWhere('id', $auth->id);
            if ($match) {
                $currentTlName = $match->name;
            }
        }

        // Kalau TL login ketemu → pakai namanya
        // Kalau tidak (admin/publik) → fallback ke TL pertama (seperti sebelumnya)
        $displayTlName = $currentTlName ?: optional($this->tourLeaders->first())->name;

        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'start_date' => $this->start_date,
            'end_date'   => $this->end_date,

            // SEKARANG dinamis:
            // - TL login: namanya sendiri
            // - selain itu: TL pertama
            'tour_leader_name' => $displayTlName,

            'tour_leaders' => $this->tourLeaders->map(function ($tl) {
                return [
                    'id'    => $tl->id,
                    'name'  => $tl->name,
                    'email' => $tl->email,
                ];
            }),

            'days_count' => $this->days()->count(),

            'days' => ItineraryDayResource::collection(
                $this->whenLoaded('days')
            ),
        ];
    }
}
