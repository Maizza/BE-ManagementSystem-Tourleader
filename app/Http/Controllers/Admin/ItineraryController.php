<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Itinerary;
use App\Models\ItineraryDay;
use App\Models\ItineraryItem;
use App\Models\TourLeader;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse; 
use Illuminate\Support\Facades\DB;

class ItineraryController extends Controller
{
    // ======================================================
    // LIST ITINERARY
    // ======================================================
    public function index()
    {
        $itineraries = Itinerary::withCount('days')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.itinerary.index', compact('itineraries'));
    }


    // ======================================================
    // FORM 1 – BUAT DRAFT ITINERARY
    // ======================================================
    public function create()
    {
        $tourLeaders = TourLeader::orderBy('name')->get();
        return view('admin.itinerary.form1', compact('tourLeaders'));
    }

    public function storeForm1(Request $request)
    {
        $data = $request->validate([
            'title'                => 'required|string|max:150',
            'start_date'           => 'nullable|date',
            'end_date'             => 'nullable|date|after_or_equal:start_date',
            'send_to'              => 'required|in:all,selected',
            'selected_tourleaders' => 'array',
            'days_count'           => 'required|integer|min:1|max:30',
        ]);

        if ($data['send_to'] === 'selected') {
            $request->validate([
                'selected_tourleaders' => 'required|array|min:1',
            ]);
        }

        session()->put('itinerary_draft', [
            'title'                => $data['title'],
            'start_date'           => $data['start_date'] ?? null,
            'end_date'             => $data['end_date'] ?? null,
            'send_to'              => $data['send_to'],
            'selected_tourleaders' => $data['selected_tourleaders'] ?? [],
            'days_count'           => $data['days_count'],
        ]);

        return redirect()->route('admin.itinerary.form2');
    }


    // ======================================================
    // FORM 2 – SET JUMLAH ITEM PER HARI
    // ======================================================
    public function form2()
    {
        $draft = session('itinerary_draft');
        if (!$draft) {
            return redirect()->route('admin.itinerary.form1')
                ->with('error', 'Draft tidak ditemukan. Mulai ulang form.');
        }

        $tourLeaders = TourLeader::orderBy('name')->get();
        return view('admin.itinerary.form2', compact('draft', 'tourLeaders'));
    }

    public function storeForm2(Request $request)
    {
        $draft = session('itinerary_draft');
        if (!$draft) {
            return redirect()->route('admin.itinerary.form1')
                ->with('error', 'Draft tidak ditemukan.');
        }

        $data = $request->validate([
            'days'              => 'required|array',
            'days.*.item_count' => 'required|integer|min:0|max:20',
        ]);

        $itinerary = null;

        DB::transaction(function () use ($draft, $data, &$itinerary) {

            // 1) BUAT ITINERARY
            $itinerary = Itinerary::create([
                'title'           => $draft['title'],
                'start_date'      => $draft['start_date'],
                'end_date'        => $draft['end_date'],
                'tour_leader_name'=> $draft['send_to'] === 'all'
                                        ? 'Semua Tour Leader'
                                        : 'Terpilih',
            ]);

            // 2) ATTACH TL
            $tlIds = $draft['send_to'] === 'all'
                ? TourLeader::pluck('id')->toArray()
                : ($draft['selected_tourleaders'] ?? []);

            $itinerary->tourLeaders()->sync($tlIds);

            // 3) BUAT DAY & ITEM-ITEM
            $daysCount = intval($draft['days_count']);

            for ($d = 1; $d <= $daysCount; $d++) {

                $day = ItineraryDay::create([
                    'itinerary_id' => $itinerary->id,
                    'day_number'   => $d,
                ]);

                $itemCount = intval($data['days'][$d - 1]['item_count'] ?? 0);

                for ($i = 1; $i <= $itemCount; $i++) {
                    ItineraryItem::create([
                        'itinerary_day_id' => $day->id,
                        'sequence'         => $i,
                    ]);
                }
            }

            session()->forget('itinerary_draft');
        });

        return redirect()->route('admin.itinerary.fill-days', $itinerary)
            ->with('ok', 'Itinerary berhasil dibuat. Silakan lengkapi detail hari.');
    }


    // ======================================================
    // FORM 3 – ISI KOTA & TANGGAL PER HARI
    // ======================================================
    public function fillDays(Itinerary $itinerary)
    {
        $itinerary->load('days.items', 'tourLeaders');
        $cities = City::orderBy('name')->get();

        return view('admin.itinerary.form3-fill-days', compact('itinerary', 'cities'));
    }

    public function saveDays(Request $request, Itinerary $itinerary)
    {
        $data = $request->validate([
            'days'        => 'required|array',
            'days.*.id'   => 'required|exists:itinerary_days,id',
            'days.*.city' => 'nullable|string|max:120',
            'days.*.date' => 'nullable|date',
        ]);

        DB::transaction(function () use ($data) {
            foreach ($data['days'] as $d) {
                ItineraryDay::where('id', $d['id'])->update([
                    'city' => $d['city'] ?? null,
                    'date' => $d['date'] ?? null,
                ]);
            }
        });

        // Arahkan ke FORM 4 (semua hari)
        return redirect()
            ->route('admin.itinerary.fill-items', $itinerary)
            ->with('ok', 'Informasi hari tersimpan. Silakan isi semua kegiatan.');
    }


    // ======================================================
    // FORM 4 – ISI SEMUA DETAIL ITEM UNTUK SEMUA HARI
    // ======================================================
    public function fillItems(Itinerary $itinerary)
    {
        $itinerary->load('days.items');

        // urutkan day
        $days = $itinerary->days->sortBy('day_number');

        return view('admin.itinerary.form4-fill-items', compact('itinerary', 'days'));
    }


    // SIMPAN SEMUA DAY SEKALIGUS
    public function saveItems(Request $request, Itinerary $itinerary)
    {
        $data = $request->validate([
            'items'            => 'required|array',
            'items.*.id'       => 'required|exists:itinerary_items,id',
            'items.*.time'     => 'nullable|date_format:H:i',
            'items.*.title'    => 'nullable|string|max:150',
            'items.*.content'  => 'nullable|string',
        ]);

        DB::transaction(function () use ($data) {
            foreach ($data['items'] as $it) {
                ItineraryItem::whereKey($it['id'])->update([
                    'time'    => $it['time'] ?? null,
                    'title'   => $it['title'] ?? null,
                    'content' => $it['content'] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('admin.itinerary.confirm', $itinerary)
            ->with('ok', 'Semua kegiatan itinerary tersimpan. Silakan cek ringkasan.');
    }


    // ======================================================
    // KONFIRMASI FINAL
    // ======================================================
    public function confirm(Itinerary $itinerary)
    {
        $itinerary->load('days.items', 'tourLeaders');
        return view('admin.itinerary.confirm', compact('itinerary'));
    }

    public function finalize(Itinerary $itinerary)
    {
        return redirect()
            ->route('admin.itinerary.show', $itinerary)
            ->with('ok', 'Itinerary telah dikonfirmasi dan tersimpan.');
    }


    // ======================================================
    // SHOW / EDIT / UPDATE
    // ======================================================
    public function show(Itinerary $itinerary)
    {
        $itinerary->load('days.items', 'tourLeaders');
        return view('admin.itinerary.show', compact('itinerary'));
    }

    public function edit(Itinerary $itinerary)
    {
        $tourLeaders = TourLeader::orderBy('name')->get();
        return view('admin.itinerary.edit', compact('itinerary', 'tourLeaders'));
    }

    public function update(Request $request, Itinerary $itinerary)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:150',
            'start_date'   => 'nullable|date',
            'end_date'     => 'nullable|date|after_or_equal:start_date',
            'tourleaders'  => 'array',
            'tourleaders.*'=> 'exists:tour_leaders,id',
        ]);

        DB::transaction(function () use ($itinerary, $data) {
            $itinerary->update([
                'title'      => $data['title'],
                'start_date' => $data['start_date'] ?? null,
                'end_date'   => $data['end_date'] ?? null,
            ]);

            $itinerary->tourLeaders()->sync($data['tourleaders'] ?? []);
        });

        return redirect()
            ->route('admin.itinerary.show', $itinerary)
            ->with('ok', 'Itinerary berhasil diperbarui.');
    }
}
