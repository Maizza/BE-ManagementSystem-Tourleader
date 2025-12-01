<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    public function store(Request $request)
    {
        $tl = $request->user(); // token TL
        if (!$tl) return response()->json(['message' => 'Unauthenticated'], 401);

        $data = $request->validate([
            'photo' => 'required|image|max:5120', // <= 5MB
            'lat'   => 'nullable|numeric',
            'lng'   => 'nullable|numeric',
        ]);

        // Ambil nama kloter dari relasi
        $kloterName = $tl->kloter ? $tl->kloter->nama : '-';

        // Simpan foto
        $path = $request->file('photo')->store('attendances', 'public');

        $att = Attendance::create([
            'tour_leader_id' => $tl->id,
            'name'           => $tl->name,
            'kloter'         => $kloterName, // ✅ isi nama kloter
            'photo_path'     => $path,
            'lat'            => $data['lat'] ?? null,
            'lng'            => $data['lng'] ?? null,
        ]);

        return response()->json([
            'success'    => true,
            'data'       => $att,
            'photo_url'  => $path ? asset('storage/'.$path) : null,
        ], 201);
    }

    // Riwayat TL sendiri
    public function myHistory(Request $request)
    {
        $tl = $request->user();
        $rows = Attendance::where('tour_leader_id', $tl->id)
            ->latest()
            ->paginate(20);

        return response()->json($rows);
    }
}
