<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Itinerary;
use App\Models\ItineraryItem;
use App\Models\ItineraryDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ItineraryResource;

class ItineraryApiController extends Controller
{
    /**
     * PUBLIC LIST /api/itinerary
     * - Digunakan admin atau akses publik
     * - Tour Leader TIDAK BOLEH pakai endpoint ini
     */
    public function index(Request $request)
    {
        $auth = $request->user();

        // Jika yang login adalah Tour Leader → filter khusus
        if ($auth instanceof \App\Models\TourLeader) {

            $q = Itinerary::query()
                ->whereHas('tourLeaders', function ($qr) use ($auth) {
                    $qr->where('tour_leader_id', $auth->id);
                })
                ->withCount('days')
                ->latest();

        } else {
            // Admin / publik → lihat semua
            $q = Itinerary::query()
                ->withCount('days')
                ->latest();
        }

        // Search
        if ($search = trim((string) $request->query('q', ''))) {
            $q->where('title', 'like', "%{$search}%");
        }

        return ItineraryResource::collection(
            $q->paginate(15)
        );
    }

    /**
     * PUBLIC SHOW /api/itinerary/{id}
     * - Jika user TL, tetap harus dicek apakah ia punya itinerary ini
     */
    public function show(Itinerary $itinerary, Request $request)
    {
        $auth = $request->user();

        if ($auth instanceof \App\Models\TourLeader) {
            $allowed = $itinerary->tourLeaders()
                ->where('tour_leader_id', $auth->id)
                ->exists();

            if (!$allowed) {
                return response()->json(['message' => 'Forbidden'], 403);
            }
        }

        $itinerary->load(['days.items', 'tourLeaders']);
        return new ItineraryResource($itinerary);
    }

    /**
     * CREATE Itinerary (Admin)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'         => 'required|string|max:150',
            'start_date'    => 'nullable|date',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
            'send_to'       => 'nullable|in:all,selected',
            'tourleaders'   => 'array',
            'tourleaders.*' => 'integer|exists:tour_leaders,id',
        ]);

        return DB::transaction(function () use ($data) {

            $itinerary = Itinerary::create([
                'title'           => $data['title'],
                'start_date'      => $data['start_date'] ?? null,
                'end_date'        => $data['end_date'] ?? null,
                'tour_leader_name'=> ($data['send_to'] ?? 'selected') === 'all'
                                        ? 'Semua Tour Leader'
                                        : 'Terpilih',
            ]);

            // Tentukan TL mana yang menerima itinerary
            if (($data['send_to'] ?? 'selected') === 'all') {
                $tlIds = \App\Models\TourLeader::pluck('id')->toArray();
            } else {
                $tlIds = $data['tourleaders'] ?? [];
            }

            $itinerary->tourLeaders()->sync($tlIds);

            return new ItineraryResource($itinerary);
        });
    }

    /**
     * UPDATE HEADER Itinerary
     */
    public function updateHeader(Itinerary $itinerary, Request $request)
    {
        $data = $request->validate([
            'title'         => 'required|string|max:150',
            'start_date'    => 'nullable|date',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
            'send_to'       => 'nullable|in:all,selected',
            'tourleaders'   => 'array',
            'tourleaders.*' => 'integer|exists:tour_leaders,id',
        ]);

        return DB::transaction(function () use ($itinerary, $data) {

            $itinerary->update([
                'title'           => $data['title'],
                'start_date'      => $data['start_date'] ?? null,
                'end_date'        => $data['end_date'] ?? null,
                'tour_leader_name'=> ($data['send_to'] ?? 'selected') === 'all'
                                        ? 'Semua Tour Leader'
                                        : 'Terpilih',
            ]);

            if (isset($data['send_to'])) {
                if ($data['send_to'] === 'all') {
                    $tlIds = \App\Models\TourLeader::pluck('id')->toArray();
                } else {
                    $tlIds = $data['tourleaders'] ?? [];
                }
                $itinerary->tourLeaders()->sync($tlIds);
            }

            return new ItineraryResource($itinerary);
        });
    }

    /**
     * Tambah hari itinerary
     */
    public function addDay(Itinerary $itinerary, Request $request)
    {
        $data = $request->validate([
            'day_number' => 'required|integer|min:1',
            'title'      => 'required|string|max:150',
        ]);

        $day = $itinerary->days()->create($data);

        return response()->json([
            'message' => 'Day added',
            'data'    => $day
        ]);
    }

    /**
     * Tambah item
     */
    public function addItem(ItineraryDay $day, Request $request)
    {
        $data = $request->validate([
            'time'        => 'required|string|max:20',
            'description' => 'required|string|max:500',
        ]);

        $item = $day->items()->create($data);

        return response()->json([
            'message' => 'Item added',
            'data'    => $item
        ]);
    }

    /**
     * DELETE
     */
    public function destroy(Itinerary $itinerary)
    {
        return DB::transaction(function () use ($itinerary) {

            $itinerary->days()->each(function ($day) {
                $day->items()->delete();
            });

            $itinerary->days()->delete();
            $itinerary->tourLeaders()->detach();
            $itinerary->delete();

            return response()->json(['message' => 'Itinerary deleted']);
        });
    }

    // ======================================================
    // ===============  KHUSUS TOUR LEADER  =================
    // ======================================================

    /**
     * List itinerary khusus TL yang login
     */
    public function tlList(Request $request)
    {
        $tl = $request->user('tourleader');

        $q = Itinerary::query()
            ->whereHas('tourLeaders', function ($qr) use ($tl) {
                $qr->where('tour_leader_id', $tl->id);
            })
            ->withCount('days')
            ->latest();

        if ($search = trim((string) $request->query('q', ''))) {
            $q->where('title', 'like', "%{$search}%");
        }

        return ItineraryResource::collection(
            $q->paginate(15)
        );
    }

    /**
     * Detail itinerary khusus TL yang login
     */
    public function tlShow(Itinerary $itinerary, Request $request)
    {
        $tl = $request->user('tourleader');

        $allowed = $itinerary->tourLeaders()
            ->where('tour_leader_id', $tl->id)
            ->exists();

        if (! $allowed) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $itinerary->load(['days.items', 'tourLeaders']);
        return new ItineraryResource($itinerary);
    }
}
