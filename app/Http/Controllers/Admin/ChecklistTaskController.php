<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kloter;
use App\Models\TourLeader;
use App\Models\ChecklistTask;
use App\Models\ChecklistQuestion;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ChecklistTaskController extends Controller
{
    // --- INDEX (list semua ceklis)
    public function index()
    {
        $tasks = ChecklistTask::withCount('questions')
            ->orderByDesc('created_at')
            ->paginate(10);

        $tasks->transform(function ($t) {
            $now = now();

            if ($now->lt($t->opens_at)) {
                $t->status = 'belum_dibuka';
            } elseif ($now->between($t->opens_at, $t->closes_at)) {
                $t->status = 'dibuka';
            } else {
                $t->status = 'ditutup';
            }

            $t->question_count = $t->questions_count ?? 0;
            return $t;
        });

        return view('admin.ceklis.index', compact('tasks'));
    }

    // --- STEP 1 ---
    public function createStep1()
    {
        return view('admin.ceklis.create-step1');
    }

    public function storeStep1(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'kloter_count' => 'required|integer|min:1|max:3',
            'question_count' => 'required|integer|min:1|max:50',
            'opens_at' => 'required|date',
            'closes_at' => 'required|date|after:opens_at',
            'target' => 'required|in:semua,tertentu',
        ]);

        Session::put('ceklis_step1', $validated);
        return redirect()->route('admin.ceklis.create.step2');
    }

    // --- STEP 2 ---
    public function createStep2()
    {
        $s1 = Session::get('ceklis_step1');
        if (!$s1) return redirect()->route('admin.ceklis.create.step1');

        $allKloters = Kloter::all();
        $allTourLeaders = TourLeader::all();

        return view('admin.ceklis.create-step2', compact('s1', 'allKloters', 'allTourLeaders'));
    }

    public function storeStep2(Request $request)
    {
        $s1 = Session::get('ceklis_step1');
        if (!$s1) return redirect()->route('admin.ceklis.create.step1');

        // ✅ Ambil otomatis semua kloter dari profil Tour Leader
        $kloterIds = TourLeader::pluck('kloter_id')->unique()->filter()->toArray();

        // ✅ Validasi hanya pertanyaan (tanpa kloter)
        $validated = $request->validate([
            'questions' => 'required|array|min:1',
            'questions.*' => 'string|max:255',
        ]);

        // ✅ Jika target tertentu, validasi tourleader_ids
        if ($s1['target'] === 'tertentu') {
            $request->validate([
                'tourleader_ids' => 'required|array|min:1',
                'tourleader_ids.*' => 'exists:tour_leaders,id',
            ]);
        }

        // ✅ Simpan ke session
        Session::put('ceklis_step2', [
            'questions' => $validated['questions'],
            'kloter_ids' => $kloterIds, // otomatis dari profil
            'tourleader_ids' => $request->tourleader_ids ?? [],
        ]);

        // ✅ Kalau target semua, langsung simpan final
        if ($s1['target'] === 'semua') {
            return $this->storeFinal($request, auto: true);
        }

        return redirect()->route('admin.ceklis.create.step3');
    }

    // --- STEP 3 (konfirmasi)
    public function createStep3()
    {
        $s1 = Session::get('ceklis_step1');
        $s2 = Session::get('ceklis_step2');
        if (!$s1 || !$s2) return redirect()->route('admin.ceklis.create.step1');

        $selectedKloters = Kloter::whereIn('id', $s2['kloter_ids'])->get();
        $selectedTourLeaders = TourLeader::whereIn('id', $s2['tourleader_ids'] ?? [])->get();
        $questions = $s2['questions'];

        return view('admin.ceklis.create-step3', compact('s1', 'selectedKloters', 'questions', 'selectedTourLeaders'));
    }

    // --- FINAL SAVE ---
    public function storeFinal(Request $request, bool $auto = false)
    {
        $s1 = Session::get('ceklis_step1');
        $s2 = Session::get('ceklis_step2');
        if (!$s1 || !$s2) return redirect()->route('admin.ceklis.create.step1');

        DB::beginTransaction();
        try {
            // Simpan task utama
            $task = ChecklistTask::create([
                'title' => $s1['title'],
                'opens_at' => Carbon::parse($s1['opens_at']),
                'closes_at' => Carbon::parse($s1['closes_at']),
                'question_count' => $s1['question_count'],
                'send_to_all' => $s1['target'] === 'semua',
            ]);

            // Simpan pertanyaan
            $order = 1;
            foreach ($s2['questions'] as $q) {
                ChecklistQuestion::create([
                    'checklist_task_id' => $task->id,
                    'order_no' => $order++,
                    'question_text' => $q,
                ]);
            }

            // Relasi kloter (otomatis)
            $task->kloters()->attach($s2['kloter_ids']);

            // Relasi tour leader
            if ($s1['target'] === 'tertentu' && isset($s2['tourleader_ids'])) {
                $task->tourleaders()->attach($s2['tourleader_ids']);
            } else {
                $task->tourleaders()->attach(TourLeader::pluck('id')->toArray());
            }

            DB::commit();

            // Bersihkan session
            Session::forget(['ceklis_step1', 'ceklis_step2']);

            return redirect()->route('admin.ceklis.index')->with('success', 'Tugas ceklis berhasil disimpan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan tugas ceklis: ' . $th->getMessage());
        }
    }

    // --- DETAIL ---
    public function show(ChecklistTask $task)
    {
        return view('admin.ceklis.show', compact('task'));
    }

    // --- HASIL ---
    public function result(ChecklistTask $task)
    {
        $allTL = DB::table('checklist_task_user')
            ->join('tour_leaders', 'tour_leaders.id', '=', 'checklist_task_user.tourleader_id')
            ->where('checklist_task_user.checklist_task_id', $task->id)
            ->select('tour_leaders.*')
            ->get();

        $sudah = $task->submissions()->with('tourleader')->get();
        $sudahIds = $sudah->pluck('tourleader_id')->toArray();
        $belum = $allTL->whereNotIn('id', $sudahIds);

        return view('admin.ceklis.result', compact('task', 'sudah', 'belum'));
    }
}
