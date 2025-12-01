<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItineraryDay;
use App\Models\ItineraryItem;
use Illuminate\Http\Request;

class DayItemController extends Controller
{
    /**
     * Tambah item baru ke suatu day
     */
    public function store(Request $request, ItineraryDay $day)
    {
        $data = $request->validate([
            'time'    => 'nullable|date_format:H:i',
            'title'   => 'nullable|string|max:150',
            'content' => 'nullable|string',
        ]);

        $lastSeq = $day->items()->max('sequence') ?? 0;

        ItineraryItem::create([
            'itinerary_day_id' => $day->id,
            'sequence'         => $lastSeq + 1,
            'time'             => $data['time'] ?? null,
            'title'            => $data['title'] ?? null,
            'content'          => $data['content'] ?? null,
        ]);

        return back()->with('ok', 'Kegiatan baru ditambahkan.');
    }

    /**
     * Update item
     */
    public function update(Request $request, ItineraryItem $item)
    {
        $data = $request->validate([
            'time'    => 'nullable|date_format:H:i',
            'title'   => 'nullable|string|max:150',
            'content' => 'nullable|string',
        ]);

        $item->update($data);

        return back()->with('ok', 'Kegiatan berhasil diperbarui.');
    }

    /**
     * Hapus item
     */
    public function destroy(ItineraryItem $item)
    {
        $item->delete();

        return back()->with('ok', 'Kegiatan telah dihapus.');
    }
}
