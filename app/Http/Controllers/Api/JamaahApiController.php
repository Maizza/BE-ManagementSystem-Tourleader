<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jamaah;
use App\Models\AttendanceJamaah;
use Illuminate\Support\Facades\Auth;

class JamaahApiController extends Controller
{
    /**
     * GET /api/tourleader/jamaah
     * Query: tanggal (Y-m-d), sesi, optional kode_kloter, nomor_bus
     */
    public function index(Request $request)
{
    $data = $request->validate([
        'tanggal'     => 'required|date_format:Y-m-d',
        'sesi'        => 'required|string|max:50',
        'kode_kloter' => 'nullable|string|max:50',
        'nomor_bus'   => 'nullable|string|max:50',
    ]);

    $tanggal   = $data['tanggal'];
    $sesi      = $data['sesi'];
    $kodeBus   = $data['nomor_bus'] ?? null;
    $kodeKltr  = $data['kode_kloter'] ?? null;

    // 1) UNTUK SEMENTARA: JANGAN FILTER DULU
    $query = Jamaah::query();

    // Kalau mau, bisa aktifkan lagi nanti ketika data sudah terisi rapi:
    /*
    if ($kodeKltr) {
        $query->where('kode_kloter', $kodeKltr);
    }
    if ($kodeBus) {
        $query->where('nomor_bus', $kodeBus);
    }
    */

    $jamaah = $query
        ->orderBy('nama_jamaah')
        ->get();

    // 2) HAPUS early-return kosong, tetap kirim debug
    $attendance = AttendanceJamaah::whereIn('jamaah_id', $jamaah->pluck('id'))
        ->where('tanggal', $tanggal)
        ->where('sesi', $sesi)
        ->orderByDesc('id')
        ->get()
        ->keyBy('jamaah_id');

    $result = $jamaah->map(function (Jamaah $j) use ($attendance) {
        $att = $attendance->get($j->id);

        return [
            'id'           => $j->id,
            'nama_jamaah'  => $j->nama_jamaah,
            'no_paspor'    => $j->no_paspor,
            'no_hp'        => $j->no_hp,
            'jenis_kelamin'=> $j->jenis_kelamin,
            'kode_kloter'  => $j->kode_kloter,
            'nomor_bus'    => $j->nomor_bus,
            'keterangan'   => $j->keterangan,
            'latest_attendance' => $att ? [
                'id'        => $att->id,
                'tanggal'   => $att->tanggal,
                'sesi'      => $att->sesi,
                'status'    => $att->status,
                'catatan'   => $att->catatan,
                'created_by'=> $att->created_by,
            ] : null,
        ];
    });

    return response()->json([
        'debug' => [
            'tanggal'        => $tanggal,
            'sesi'           => $sesi,
            'req_kode_kloter'=> $kodeKltr,
            'req_nomor_bus'  => $kodeBus,
            'total_jamaah'   => Jamaah::count(),
            'total_filtered' => $jamaah->count(),
        ],
        'data' => $result,
    ]);
}


    /**
     * POST /api/tourleader/jamaah-attendance
     * Body: jamaah_id, tanggal(Y-m-d), sesi, status, catatan?
     */
    public function setAttendance(Request $request)
    {
        $data = $request->validate([
            'jamaah_id' => 'required|exists:jamaahs,id',
            'tanggal'   => 'required|date_format:Y-m-d',
            'sesi'      => 'required|string|max:50',
            'status'    => 'required|in:hadir,tidak_hadir,izin',
            'catatan'   => 'nullable|string|max:255',
        ]);

        $userId = Auth::id(); // di guard tourleader, ini ID TL; boleh dipakai isi created_by

        // update / create berdasarkan kombinasi jamaah + tanggal + sesi
        $attendance = AttendanceJamaah::updateOrCreate(
            [
                'jamaah_id' => $data['jamaah_id'],
                'tanggal'   => $data['tanggal'],
                'sesi'      => $data['sesi'],
            ],
            [
                'status'     => $data['status'],
                'catatan'    => $data['catatan'] ?? null,
                'created_by' => $userId,
            ]
        );

        return response()->json([
            'success'    => true,
            'attendance' => [
                'id'         => $attendance->id,
                'jamaah_id'  => $attendance->jamaah_id,
                'tanggal'    => $attendance->tanggal,
                'sesi'       => $attendance->sesi,
                'status'     => $attendance->status,
                'catatan'    => $attendance->catatan,
                'created_by' => $attendance->created_by,
            ],
        ]);
    }
}
