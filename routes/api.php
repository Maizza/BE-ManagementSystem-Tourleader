<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ScanController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\TourLeaderController;
use App\Http\Controllers\FCMTokenController;
use App\Http\Controllers\Api\TaskApiController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\ChecklistTaskApiController;
use App\Http\Controllers\Api\ChecklistSubmitController;
use App\Http\Controllers\Api\ItineraryApiController;
use App\Http\Controllers\Api\JamaahApiController; 


// ======================================================
// ===============  AUTH (USER)  ========================
// ======================================================
Route::post('/login', [AuthController::class, 'login']);


// ======================================================
// ===============  ITINERARY (public read)  ============
// ======================================================
Route::get('/itinerary', [ItineraryApiController::class, 'index']);           // GET list + ?q=
Route::get('/itinerary/{itinerary}', [ItineraryApiController::class, 'show']); // GET detail (days+items)


// ======================================================
// ===============  AREA AUTH SANCTUM (USER)  ===========
// ======================================================
Route::middleware('auth:sanctum')->group(function () {

    // ----------------------------
    // Scan koper
    // ----------------------------
    Route::get('/scans', [ScanController::class, 'index']);
    Route::post('/scans', [ScanController::class, 'store']);

    // ----------------------------
    // FCM Token
    // ----------------------------
    Route::post('/save-fcm-token', function (Request $request) {
        $data = $request->validate([
            'fcm_token' => 'required|string',
            'platform'  => 'nullable|string',
        ]);

        $user = $request->user();
        $user->update(['fcm_token' => $data['fcm_token']]);

        if (($data['platform'] ?? '') !== 'web') {
            $key = config('services.fcm.server_key');
            if ($key) {
                \Illuminate\Support\Facades\Http::withHeaders([
                    'Authorization' => 'key=' . $key,
                    'Content-Type'  => 'application/json',
                ])->post('https://iid.googleapis.com/iid/v1:batchAdd', [
                    'to' => '/topics/all',
                    'registration_tokens' => [$data['fcm_token']],
                ]);
            }
        }

        return response()->json(['success' => true]);
    });

    // ----------------------------
    // Notifications
    // ----------------------------
    Route::get('/notifications', [NotificationController::class, 'list']);
    Route::post('/notifications/send', [NotificationController::class, 'send']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);

    // ----------------------------
    // ITINERARY (CRUD & Steps)
    // ----------------------------
    Route::post('/itinerary', [ItineraryApiController::class, 'store']);                        // Step 1 - create
    Route::put('/itinerary/{itinerary}', [ItineraryApiController::class, 'updateHeader']);      // Update header
    Route::put('/itinerary/{itinerary}/day-config', [ItineraryApiController::class, 'setDayConfig']); // Step 2 - config
    Route::put('/itinerary/{itinerary}/days/{dayNumber}', [ItineraryApiController::class, 'fillDay']); // Step 3 - isi hari
    Route::delete('/itinerary/{itinerary}', [ItineraryApiController::class, 'destroy']);        // Delete itinerary


    Route::get('/jamaah', [JamaahApiController::class, 'index']);
});


// ======================================================
// ===============  TOUR LEADER (auth:tourleader)  =======
// ======================================================
Route::post('/tourleader/login', [TourLeaderController::class, 'login']);

Route::middleware('auth:tourleader')->group(function () {

    // Profil TL
    Route::get('/tourleader/profile', [TourLeaderController::class, 'profile']);

    // Scan koper TL
    Route::get('/tourleader/scans', [ScanController::class, 'index']);
    Route::post('/tourleader/scans', [ScanController::class, 'store']);

    // FCM token TL
    Route::post('/tourleader/fcm-token', [FCMTokenController::class, 'store']);
    Route::delete('/tourleader/fcm-token', [FCMTokenController::class, 'destroy']);

    // Task TL
    Route::get('/tourleader/tasks', [TaskApiController::class, 'index']);
    Route::get('/tourleader/tasks/{task}', [TaskApiController::class, 'show']);
    Route::post('/tourleader/tasks/{task}/done', [TaskApiController::class, 'markDone']);

    // Checklist TL
    Route::get('/tourleader/checklist', [ChecklistTaskApiController::class, 'index']);
    Route::get('/tourleader/checklist/{task}', [ChecklistTaskApiController::class, 'show']);
    Route::post('/tourleader/checklist/{task}/submit', [ChecklistSubmitController::class, 'submit']);

    // Itinerary untuk TourLeader (hanya itinerary yang ditugaskan ke dia)
    Route::get('/tourleader/itinerary', [ItineraryApiController::class, 'tlList']);
    Route::get('/tourleader/itinerary/{itinerary}', [ItineraryApiController::class, 'tlShow']);


    // ----------------------------
    // JAMA'AH (untuk TL - Flutter)
    // ----------------------------

    // List jamaah (TL bisa filter pakai ?kloter=ADZ-08)
    // List jamaah + latest attendance
    Route::get('/tourleader/jamaah', [JamaahApiController::class, 'index']);

    // Set / update absensi jamaah
    Route::post('/tourleader/jamaah-attendance', [JamaahApiController::class, 'setAttendance']);

});


// ======================================================
// ===============  ATTENDANCE (shared TL/User)  =========
// ======================================================
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/tourleader/attendance', [AttendanceController::class, 'store']);
    Route::get('/tourleader/attendance', [AttendanceController::class, 'myHistory']);
});
