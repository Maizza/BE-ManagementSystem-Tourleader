<?php

namespace App\Exports;

use App\Models\Scan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ScansExport implements FromView
{
    protected $tourleader;
    protected $date;

    public function __construct($tourleader = null, $date = null)
    {
        $this->tourleader = $tourleader;
        $this->date = $date;
    }

    public function view(): View
    {
        $query = Scan::with('user')->latest();

        if ($this->tourleader) {
            $query->where('user_id', $this->tourleader);
        }

        if ($this->date) {
            $query->whereDate('scanned_at', $this->date);
        }

        $scans = $query->get();

        return view('admin.scans.export', compact('scans'));
    }
}
