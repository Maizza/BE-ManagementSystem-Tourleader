<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{ChecklistTask, ChecklistSubmission, ChecklistAnswer};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChecklistSubmitController extends Controller
{
    public function submit(Request $r, ChecklistTask $task) {
    $tl = $r->user('tourleader');

    if (now()->lt($task->opens_at)) {
        return response()->json(['message' => 'Tugas belum dibuka'], 422);
    }
    if (now()->gt($task->closes_at)) {
        return response()->json(['message' => 'Tugas sudah ditutup'], 422);
    }

    $assigned = DB::table('checklist_task_user')
        ->where('checklist_task_id', $task->id)
        ->where('tourleader_id', $tl->id)
        ->first();

    if (!$assigned) return response()->json(['message' => 'Tidak ditugaskan'], 404);
    if ($assigned->done_at) return response()->json(['message' => 'Sudah dikerjakan'], 200);

    $data = $r->validate([
        // ⬇️ kita tak lagi mengandalkan input 'kloter' dari client
        'nama_petugas' => 'required|string|max:255',
        'answers'      => 'required|array|min:1',
        'answers.*.checklist_question_id' => 'required|integer|exists:checklist_questions,id',
        'answers.*.value' => 'required|in:sudah,tidak,rekan',
        'answers.*.note'  => 'nullable|string|max:500',
    ]);

    // Ambil NAMA kloter dari relasi Tourleader -> Kloter
    $kloterName = optional($tl->kloter)->nama ?? '-';

    // pastikan semua question milik task
    $own = $task->questions()->pluck('id')->all();
    $incoming = collect($data['answers'])->pluck('checklist_question_id')->all();
    if (array_diff($incoming, $own)) {
        return response()->json(['message' => 'Jawaban tidak valid'], 422);
    }

    DB::transaction(function () use ($task, $tl, $data, $kloterName) {
        $sub = \App\Models\ChecklistSubmission::create([
            'checklist_task_id' => $task->id,
            'tourleader_id'     => $tl->id,
            'nama_petugas'      => $data['nama_petugas'],
            // ⬇️ simpan nama kloter sebenarnya
            'kloter'            => $kloterName,
            'submitted_at'      => now(),
        ]);

        foreach ($data['answers'] as $a) {
            \App\Models\ChecklistAnswer::create([
                'checklist_submission_id' => $sub->id,
                'checklist_question_id'   => $a['checklist_question_id'],
                'value'                   => $a['value'],
                'note'                    => $a['note'] ?? null,
            ]);
        }

        DB::table('checklist_task_user')
            ->where('checklist_task_id', $task->id)
            ->where('tourleader_id', $tl->id)
            ->update(['done_at' => now()]);
    });

    return response()->json(['success' => true, 'done_at' => now()->toIso8601String()]);
}
}
