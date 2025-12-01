<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskQuestion;
use App\Models\TourLeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskWizardController extends Controller
{
    // List tugas
    public function index()
    {
        $tasks = Task::latest()->paginate(10);
        return view('admin.tugas_tourleader.index', compact('tasks'));
    }

    // STEP 1: form judul, jumlah soal, waktu, dan target
    public function createStep1()
    {
        return view('admin.tugas_tourleader.create-step1');
    }

    public function storeStep1(Request $r)
    {
        $data = $r->validate([
            'title'          => ['required', 'string', 'max:255'],
            'question_count' => ['required', 'integer', 'min:1', 'max:50'],
            'opens_at'       => ['required', 'date'],
            'closes_at'      => ['required', 'date', 'after:opens_at'],
            'target_type'    => ['required', 'in:all,specific'],
        ]);

        session(['task_step1' => $data]);
        return redirect()->route('admin.tasks.create.step2');
    }

    // STEP 2: form soal + pilih tour leader (kalau specific)
    public function createStep2()
{
    $s1 = session('task_step1');
    abort_unless($s1, 302, 'Step 1 belum diisi.');

    // ✅ Ambil tour leader hanya jika target_type = 'specific'
    $tourLeaders = [];
    if (($s1['target_type'] ?? '') === 'specific') {
        // Pastikan hanya ambil kolom penting
        $tourLeaders = \App\Models\TourLeader::select('id', 'name')->orderBy('name')->get();
    }

    return view('admin.tugas_tourleader.create-step2', [
        's1' => $s1,
        'tourLeaders' => $tourLeaders,
    ]);
}


    public function storeStep2(Request $r)
    {
        $s1 = session('task_step1');
        abort_unless($s1, 302, 'Step 1 belum diisi.');

        // Validasi pertanyaan
        $rules = [];
        for ($i = 0; $i < (int) $s1['question_count']; $i++) {
            $rules["questions.$i"] = ['required', 'string', 'max:2000'];
        }

        // Jika target specific, pastikan pilih tour leader
        if (($s1['target_type'] ?? '') === 'specific') {
            $rules['tour_leaders'] = ['required', 'array', 'min:1'];
            $rules['tour_leaders.*'] = ['exists:tour_leaders,id'];
        }

        $data = $r->validate($rules);

        DB::transaction(function () use ($s1, $data, $r) {
    // 1) buat tugas
    $task = Task::create($s1);

    // 2) simpan soal
    foreach ($data['questions'] as $idx => $text) {
        TaskQuestion::create([
            'task_id'       => $task->id,
            'order_no'      => $idx + 1,
            'question_text' => $text,
        ]);
    }

    // 3) assign ke tour leader
    if ($s1['target_type'] === 'all') {
        // Semua TL
        $tourleaders = \App\Models\TourLeader::pluck('id');
        $task->tourleaders()->attach($tourleaders->all());
    } else {
        // Tertentu (yang dicentang di step 2)
        $selected = $r->input('tour_leaders', []);
        if (!empty($selected)) {
            $task->tourleaders()->attach($selected);
        }
    }
});


        session()->forget('task_step1');

        return redirect()->route('admin.tasks.index')
            ->with('success', 'Tugas berhasil dibuat & dikirimkan sesuai target.');
    }

    // Detail tugas
    public function show(Task $task)
    {
        $task->load('questions');
        return view('admin.tugas_tourleader.show', compact('task'));
    }
}
