<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    /**
     * Tampilkan daftar riwayat absensi TL.
     * - Eager load: tourleader -> kloter
     * - Urut terbaru
     * - Paginate 20 per halaman
     */
    public function index()
    {
        $rows = Attendance::with(['tourleader.kloter'])
            ->latest()               // order by created_at desc
            ->paginate(20);          // sesuaikan kebutuhan

        return view('admin.attendances.index', compact('rows'));
    }
}
