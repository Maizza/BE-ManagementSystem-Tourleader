<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChecklistTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChecklistTaskApiController extends Controller
{
    // GET /api/tourleader/checklist
    public function index(Request $r)
    {
        $tl = $r->user('tourleader');

        $tasks = ChecklistTask::select(
                'checklist_tasks.id',
                'checklist_tasks.title',
                'checklist_tasks.question_count',
                'checklist_tasks.opens_at',
                'checklist_tasks.closes_at',
                // ambil kolom done_at dari pivot
                'checklist_task_user.done_at as pivot_done_at'
            )
            ->join('checklist_task_user', 'checklist_task_user.checklist_task_id', '=', 'checklist_tasks.id')
            ->where('checklist_task_user.tourleader_id', $tl->id)
            ->orderByDesc('checklist_tasks.opens_at')
            ->get();

        $now = now();

        $data = $tasks->map(function ($t) use ($now) {
            $status = $now->lt($t->opens_at)
                ? 'belum_dibuka'
                : ($now->gt($t->closes_at) ? 'ditutup' : 'dibuka');

            // pivot_done_at dari join bisa string/null → parse aman
            $doneAtIso = $t->pivot_done_at
                ? Carbon::parse($t->pivot_done_at)->toIso8601String()
                : null;

            return [
                'id'             => $t->id,
                'title'          => $t->title,
                'question_count' => $t->question_count,
                'opens_at'       => $t->opens_at->toIso8601String(),
                'closes_at'      => $t->closes_at->toIso8601String(),
                'status'         => $status,
                'done_at'        => $doneAtIso,
                'can_work'       => $status === 'dibuka' && is_null($t->pivot_done_at),
                'type'           => 'checklist',
            ];
        });

        return response()->json(['data' => $data]);
    }

    // GET /api/tourleader/checklist/{task}
    public function show(Request $r, ChecklistTask $task)
    {
        $tl = $r->user('tourleader');

        $assigned = DB::table('checklist_task_user')
            ->where('checklist_task_id', $task->id)
            ->where('tourleader_id', $tl->id)
            ->first();

        if (!$assigned) {
            return response()->json(['message' => 'Tugas tidak ditemukan untuk akun ini'], 404);
        }

        $task->load(['questions' => function ($q) {
            $q->orderBy('order_no');
        }]);

        $now = now();
        $status = $now->lt($task->opens_at)
            ? 'belum_dibuka'
            : ($now->gt($task->closes_at) ? 'ditutup' : 'dibuka');

        $doneAtIso = $assigned->done_at
            ? Carbon::parse($assigned->done_at)->toIso8601String()
            : null;

        return response()->json([
            'id'             => $task->id,
            'title'          => $task->title,
            'question_count' => $task->question_count,
            'type'           => 'checklist',
            'opens_at'       => $task->opens_at->toIso8601String(),
            'closes_at'      => $task->closes_at->toIso8601String(),
            'status'         => $status,
            'done_at'        => $doneAtIso,
            'questions'      => $task->questions->map(fn ($q) => [
                'id'            => $q->id,
                'order_no'      => $q->order_no,
                'question_text' => $q->question_text,
            ]),
        ]);
    }
}
