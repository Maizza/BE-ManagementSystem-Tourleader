<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TourLeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class TaskApiController extends Controller
{
    // GET /tourleader/tasks
   public function index(Request $request)
{
    $tl = $request->user('tourleader'); // guard: tourleader
    $now = now();

    $tasks = $tl->tasks()
        ->withPivot('done_at')
        ->orderByDesc('opens_at')
        ->get();

    $data = $tasks->map(function ($t) use ($now) {
        $status = $now->lt($t->opens_at)
            ? 'belum_dibuka'
            : ($now->gt($t->closes_at) ? 'ditutup' : 'dibuka');

        // pastikan done_at diparse ke ISO8601 atau null
        $doneAt = $t->pivot && $t->pivot->done_at
            ? Carbon::parse($t->pivot->done_at)->toIso8601String()
            : null;

        return [
            'id'             => $t->id,
            'title'          => $t->title,
            'question_count' => $t->question_count,
            'opens_at'       => $t->opens_at->toIso8601String(),
            'closes_at'      => $t->closes_at->toIso8601String(),
            'status'         => $status,
            'done_at'        => $doneAt,
            'can_work'       => $status === 'dibuka' && is_null($doneAt),
        ];
    });

    return response()->json(['data' => $data]);
}


    // GET /tourleader/tasks/{task}
   public function show(Request $request, Task $task)
{
    $tl = $request->user('tourleader');

    // pastikan task memang di-assign ke TL ini
    $assigned = DB::table('task_user')
        ->where('task_id', $task->id)
        ->where('tourleader_id', $tl->id)
        ->first();

    if (!$assigned) {
        return response()->json(['message' => 'Tugas tidak ditemukan untuk akun ini'], 404);
    }

    // Kalau sudah done, biarkan saja bisa lihat
    $task->load(['questions' => fn ($q) => $q->orderBy('order_no')]);

    $now    = now();
    $status = $now->lt($task->opens_at) ? 'belum_dibuka' : ($now->gt($task->closes_at) ? 'ditutup' : 'dibuka');

    return response()->json([
        'id'             => $task->id,
        'title'          => $task->title,
        'question_count' => $task->question_count,
        'opens_at'       => $task->opens_at->toIso8601String(),
        'closes_at'      => $task->closes_at->toIso8601String(),
        'status'         => $status,
        'done_at'        => $assigned->done_at ? \Carbon\Carbon::parse($assigned->done_at)->toIso8601String() : null,
        'questions'      => $task->questions->map(fn($q) => [
            'order_no'      => $q->order_no,
            'question_text' => $q->question_text,
        ]),
    ]);
}

    // POST /tourleader/tasks/{task}/done
    public function markDone(Request $request, Task $task)
    {
        $tl = $request->user('tourleader');

        // validasi assignment
        $pivot = DB::table('task_user')
            ->where('task_id', $task->id)
            ->where('tourleader_id', $tl->id);

        $row = $pivot->first();
        if (!$row) {
            return response()->json(['message' => 'Tugas tidak ditemukan untuk akun ini'], 404);
        }

        // cek window waktu
        $now = now();
        if ($now->lt($task->opens_at)) {
            return response()->json(['message' => 'Tugas belum dibuka'], 422);
        }
        if ($now->gt($task->closes_at)) {
            return response()->json(['message' => 'Tugas sudah ditutup'], 422);
        }

        // sudah done?
        if (!is_null($row->done_at)) {
            return response()->json(['message' => 'Sudah ditandai dikerjakan'], 200);
        }

        $pivot->update(['done_at' => $now]);

        return response()->json(['success' => true, 'done_at' => $now->toIso8601String()]);
    }
}
