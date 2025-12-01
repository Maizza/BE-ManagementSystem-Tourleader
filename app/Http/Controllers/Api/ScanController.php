<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Scan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ScanController extends Controller
{
    // Ambil list scan sesuai tour leader login
    public function index(Request $request)
{
    $tourleaderId = auth('tourleader')->id();
    if (!$tourleaderId) {
        return response()->json([
            'status'  => 'error',
            'message' => 'Unauthorized: gunakan token tour leader.',
        ], 401);
    }

    return Scan::query()
        ->with('tourleader:id,name')
        ->where('tourleader_id', $tourleaderId)
        ->latest('scanned_at')
        ->get(['id','koper_code','owner_name','owner_phone','scanned_at','kloter','tourleader_id']);
}

    // Simpan scan baru
   public function store(Request $request)
{

    $tourleaderId = auth('tourleader')->id();
    if (!$tourleaderId) {
        // Biar jelas kalau tokennya salah/expired/guard salah
        return response()->json([
            'status'  => 'error',
            'message' => 'Unauthorized: gunakan token tour leader.',
        ], 401);
    }

    $payload = $request->all();
    if ($request->filled('qr_text')) {
        $payload = array_merge($payload, $this->parseQr($request->string('qr_text')));
    }

    $data = validator($payload, [
        'koper_code'  => 'required|string|max:255',
        'owner_name'  => 'nullable|string|max:255',
        'owner_phone' => 'nullable|string|max:30',
        'scanned_at'  => 'nullable|date',
        'kloter'      => 'nullable|string|max:255',
    ])->validate();

    // Cek existing untuk TL ini
    $existing = Scan::where('tourleader_id', $tourleaderId)
        ->where('koper_code', $data['koper_code'])
        ->first();

    if ($existing) {
        if (empty($existing->kloter) && !empty($data['kloter'])) {
            $existing->kloter = $data['kloter'];
            if (!empty($data['owner_name']))  $existing->owner_name  = $data['owner_name'];
            if (!empty($data['owner_phone'])) $existing->owner_phone = $data['owner_phone'];
            $existing->save();

            return response()->json([
                'status'  => 'updated',
                'message' => 'Data kloter diperbarui pada scan yang sudah ada.',
                'data'    => $existing
            ], 200);
        }

        return response()->json([
            'status'  => 'error',
            'message' => 'Koper ini sudah pernah discan oleh tour leader ini.'
        ], 409);
    }

    // Insert baru
    $scan = Scan::create([
        'tourleader_id'=> $tourleaderId,
        'koper_code'   => $data['koper_code'],
        'owner_name'   => $data['owner_name'] ?? null,
        'owner_phone'  => $data['owner_phone'] ?? null,
        'scanned_at'   => isset($data['scanned_at'])
            ? \Illuminate\Support\Carbon::parse($data['scanned_at'])
            : now('Asia/Jakarta'),
        'kloter'       => $data['kloter'] ?? null,
    ]);

    return response()->json([
        'status'  => 'success',
        'message' => 'Scan berhasil disimpan.',
        'data'    => $scan
    ], 201);
}
    // parseQr: dukung 4 bagian & '|'
    private function parseQr(string $raw): array
    {
        $raw = trim($raw);
        if ($raw === '') return [];

        if ($this->isJson($raw)) {
            $j = json_decode($raw, true);
            return array_filter([
                'koper_code'  => $j['kode']        ?? $j['koper_code']  ?? null,
                'owner_name'  => $j['nama']        ?? $j['owner_name']  ?? null,
                'owner_phone' => $j['phone']       ?? $j['owner_phone'] ?? null,
                'kloter'      => $j['kloter']      ?? null,
            ], fn($v) => filled($v));
        }

        $parts = preg_split('/\||\r\n|\n|\r/', $raw);
        $parts = array_map('trim', $parts);

        if (count($parts) >= 3) {
            return [
                'koper_code'  => $parts[0] ?? null,
                'owner_name'  => $parts[1] ?? null,
                'owner_phone' => $parts[2] ?? null,
                'kloter'      => $parts[3] ?? null,
            ];
        }

        return [];
    }

    private function isJson(string $s): bool
    {
        json_decode($s);
        return json_last_error() === JSON_ERROR_NONE;
    }
}
