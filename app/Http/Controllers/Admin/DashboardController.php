<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Scan;
use App\Models\Tourleader; // ← GUNAKAN Tourleader, bukan User
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalScans = Scan::count();
        $totalTourLeaders = Tourleader::count(); // ← Hitung langsung dari Tourleader
        $latestScans = Scan::with('tourleader')->latest()->take(5)->get(); // ← GUNAKAN 'tourleader'

        return view('admin.dashboard', compact('totalScans', 'totalTourLeaders', 'latestScans'));
    }

    public function dashboard() 
    {
        $scanToday = Scan::whereDate('created_at', today())->count();
        $totalScan = Scan::count();
        $lastScan = Scan::latest()->first();
        $lastScanDate = $lastScan ? $lastScan->created_at->format('d M Y H:i') : null;

        return view('dashboard', compact('scanToday', 'totalScan', 'lastScanDate'));
    }
}