<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItineraryDay;
use Illuminate\Http\Request;

class DayController extends Controller
{
    /**
     * Update data Day (city, date)
     */
    public function update(Request $request, ItineraryDay $day)
    {
        $data = $request->validate([
            'city' => 'nullable|string|max:120',
            'date' => 'nullable|date',
        ]);

        $day->update($data);

        return back()->with('ok', 'Hari berhasil diperbarui.');
    }
}
