<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Scan;
use App\Models\TourLeader;
use Illuminate\Http\Request;
use App\Exports\ScansExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Carbon;

class ScanController extends Controller
{
    // -----------------------------
    // INDEX (List data scan)
    // -----------------------------
    public function index(Request $request)
    {
        $query = Scan::query()->with('tourleader:id,name');

        // Filter Tour Leader
        if ($request->filled('tourleader')) {
            $query->where('tourleader_id', $request->tourleader);
        }

        // Filter Tanggal
        if ($request->filled('date')) {
            $query->whereDate('scanned_at', $request->date);
        }

        $scans = $query->orderBy('koper_code', 'asc')->get();

        // (Opsional) Cek urutan koper
        if ($request->has('check_order')) {
            $filtered = collect();
            $expected = 1;
            foreach ($scans as $scan) {
                $number = (int) filter_var($scan->koper_code, FILTER_SANITIZE_NUMBER_INT);
                if ($number === $expected) {
                    $filtered->push($scan);
                    $expected++;
                } else {
                    break;
                }
            }
            $scans = $filtered;
        }

        $tourleaders = TourLeader::all();

        // Support JSON & web view
        if ($request->wantsJson()) {
            return response()->json(['data' => $scans], 200);
        }

        return view('admin.scans.index', compact('scans', 'tourleaders'));
    }

    // -----------------------------
    // EXPORT KE EXCEL
    // -----------------------------
    public function export(Request $request)
    {
        return Excel::download(
            new ScansExport($request->input('tourleader'), $request->input('date')),
            'scans.xlsx'
        );
    }

    // -----------------------------
    // SIMPAN SCAN BARU
    // -----------------------------
    public function store(Request $request)
    {
        // Validasi input dasar
        $request->validate([
            'qr_text' => 'nullable|string|max:500',
        ]);

        // Ambil data dasar
        $payload = $request->only(['qr_text', 'koper_code', 'owner_name', 'owner_phone', 'scanned_at', 'kloter']);

        // Jika QR text dikirim → parse otomatis
        if ($request->filled('qr_text')) {
            $payload = array_merge($payload, $this->parseQr($request->string('qr_text')));
        }

        // Validasi hasil parse
        $data = validator($payload, [
            'koper_code'  => 'required|string|max:255',
            'owner_name'  => 'nullable|string|max:255',
            'owner_phone' => 'nullable|string|max:30',
            'scanned_at'  => 'nullable|date',
            'kloter'      => 'nullable|string|max:255',
        ])->validate();

        if (empty($data['koper_code'])) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Kode koper tidak ditemukan dalam QR atau input.',
            ], 422);
        }

        // Ambil ID tour leader dari guard
        $tourleaderId = auth('tourleader')->id();

        // Cek duplikat oleh TL yang sama
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
                    'status'  => 'success',
                    'message' => 'Data kloter diperbarui pada scan yang sudah ada.',
                    'data'    => $existing
                ], 200);
            }

            return response()->json([
                'status'  => 'error',
                'message' => 'Koper ini sudah pernah discan oleh tour leader ini.'
            ], 409);
        }

        // Cek duplikat oleh TL lain (opsional tapi disarankan)
        $duplicateByOthers = Scan::where('koper_code', $data['koper_code'])
            ->where('tourleader_id', '!=', $tourleaderId)
            ->first();

        if ($duplicateByOthers) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Koper ini sudah pernah discan oleh tour leader lain.',
            ], 409);
        }

        // Simpan data baru
        $scan = Scan::create([
            'tourleader_id' => $tourleaderId,
            'koper_code'    => $data['koper_code'],
            'owner_name'    => $data['owner_name'] ?? null,
            'owner_phone'   => $data['owner_phone'] ?? null,
            'scanned_at'    => isset($data['scanned_at'])
                ? Carbon::parse($data['scanned_at'])->setTimezone('Asia/Jakarta')
                : now('Asia/Jakarta'),
            'kloter'        => $data['kloter'] ?? null,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Scan berhasil disimpan.',
            'data'    => $scan
        ], 201);
    }

    // -----------------------------
    // PARSE DATA QR
    // -----------------------------
    private function parseQr(string $raw): array
    {
        $raw = trim($raw);
        if ($raw === '') return [];

        // Format JSON
        if ($this->isJson($raw)) {
            $j = json_decode($raw, true);
            return array_filter([
                'koper_code'  => $j['kode']        ?? $j['koper_code']  ?? null,
                'owner_name'  => $j['nama']        ?? $j['owner_name']  ?? null,
                'owner_phone' => $j['phone']       ?? $j['owner_phone'] ?? null,
                'kloter'      => $j['kloter']      ?? null,
            ], fn($v) => filled($v));
        }

        // Format teks dengan pemisah "|" atau baris
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
