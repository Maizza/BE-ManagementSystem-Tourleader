<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;

class TaskResultController extends Controller
{
    // Halaman rekap: siapa sudah / belum
    public function show(Task $task)
    {
        $task->load(['doneTourleaders','notDoneTourleaders']);
        $done    = $task->doneTourleaders()->get();
        $notDone = $task->notDoneTourleaders()->get();

        return view('admin.tugas_tourleader.result', compact('task','done','notDone'));
    }
}
