<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TourLeaderController;
use App\Http\Controllers\Admin\ScanController;
use App\Http\Controllers\Admin\KloterController;
use App\Http\Controllers\Admin\TaskWizardController;
use App\Http\Controllers\Admin\TaskResultController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\ChecklistTaskController;
use App\Http\Controllers\Admin\ItineraryController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\JamaahController;

// ======================================================
// Redirect root -> dashboard
// ======================================================
Route::get('/', fn() => redirect('/dashboard'));

Auth::routes();


// ======================================================
// ===============  AUTH AREA  ==========================
// ======================================================
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    // ======================================================
    // ===============  MASTER DATA  ========================
    // ======================================================
    Route::resource('/tourleaders', TourLeaderController::class);
    Route::resource('/kloter', KloterController::class);

    // JAMA'AH
    Route::get('/jamaah', [JamaahController::class, 'index'])->name('jamaah.index');

   // Form upload Excel
   Route::get('/jamaah/import', [JamaahController::class, 'importForm'])->name('jamaah.importForm');

   // Proses upload Excel
   Route::post('/jamaah/import', [JamaahController::class, 'import'])->name('jamaah.import');

    // ======================================================
    // ===============  RIWAYAT SCAN  =======================
    // ======================================================
    Route::get('/scans', [ScanController::class, 'index'])->name('scans.index');
    Route::get('/scans/export', [ScanController::class, 'export'])->name('scans.export');


    // ======================================================
    // ===============  ADMIN AREA  =========================
    // ======================================================
    Route::prefix('admin')->name('admin.')->group(function () {


        // DAY update
    Route::put('/day/{day}',
        [\App\Http\Controllers\Admin\DayController::class, 'update']
    )->name('day.update');

    // DAY ITEM
    Route::post('/day/{day}/item',
        [\App\Http\Controllers\Admin\DayItemController::class, 'store']
    )->name('day.item.store');

    Route::put('/day/item/{item}',
        [\App\Http\Controllers\Admin\DayItemController::class, 'update']
    )->name('day.item.update');

    Route::delete('/day/item/{item}',
        [\App\Http\Controllers\Admin\DayItemController::class, 'destroy']
    )->name('day.item.destroy');

        // --------------------------------------------------------------------
        // NOTIFICATIONS
        // --------------------------------------------------------------------
        Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::get('notifications/create', [NotificationController::class, 'create'])->name('notifications.create');
        Route::post('notifications/send', [NotificationController::class, 'sendNotification'])->name('notifications.send');


        // --------------------------------------------------------------------
        // TUGAS (Step Wizard — Soal & Rekap)
        // --------------------------------------------------------------------
        Route::prefix('tugas')->name('tasks.')->group(function () {
            Route::get('/', [TaskWizardController::class, 'index'])->name('index');

            // Step 1
            Route::get('/create', [TaskWizardController::class, 'createStep1'])->name('create.step1');
            Route::post('/create', [TaskWizardController::class, 'storeStep1'])->name('store.step1');

            // Step 2 (Soal)
            Route::get('/create/soal', [TaskWizardController::class, 'createStep2'])->name('create.step2');
            Route::post('/create/soal', [TaskWizardController::class, 'storeStep2'])->name('store.step2');

            // Detail & Hasil
            Route::get('/{task}', [TaskWizardController::class, 'show'])->name('show');
            Route::get('/{task}/hasil', [TaskResultController::class, 'show'])->name('result');
        });


        // --------------------------------------------------------------------
        // CEKLIS (3 Step Wizard)
        // --------------------------------------------------------------------
        Route::prefix('ceklis')->name('ceklis.')->group(function () {

            Route::get('/', [ChecklistTaskController::class,'index'])->name('index');

            // Step 1
            Route::get('/create', [ChecklistTaskController::class,'createStep1'])->name('create.step1');
            Route::post('/create', [ChecklistTaskController::class,'storeStep1'])->name('store.step1');

            // Step 2 (Soal)
            Route::get('/create/soal', [ChecklistTaskController::class,'createStep2'])->name('create.step2');
            Route::post('/create/soal', [ChecklistTaskController::class,'storeStep2'])->name('store.step2');

            // Step 3 (Konfirmasi)
            Route::get('/create/konfirmasi', [ChecklistTaskController::class,'createStep3'])->name('create.step3');
            Route::post('/create/konfirmasi', [ChecklistTaskController::class,'storeFinal'])->name('store.final');

            // Detail & hasil
            Route::get('/{task}', [ChecklistTaskController::class,'show'])->name('show');
            Route::get('/{task}/hasil', [ChecklistTaskController::class,'result'])->name('result');
        });

        // --------------------------------------------------------------------
        // KOTA ITINERARY
        // --------------------------------------------------------------------
        Route::prefix('itinerary/kota')->name('itinerary.kota.')->group(function () {

            // HARUS PALING ATAS
            Route::get('/', [CityController::class, 'index'])->name('index');

            Route::get('/create', [CityController::class, 'create'])->name('create');
            Route::post('/store', [CityController::class, 'store'])->name('store');

            // Route dinamis (HARUS DI BAWAH)
            Route::get('/{city}/edit', [CityController::class, 'edit'])->name('edit');
            Route::put('/{city}', [CityController::class, 'update'])->name('update');
            Route::delete('/{city}', [CityController::class, 'destroy'])->name('destroy');
    });

        // --------------------------------------------------------------------
        // ITINERARY (2 step wizard)
        // --------------------------------------------------------------------
     Route::prefix('itinerary')->name('itinerary.')->group(function () {
    Route::get('/', [ItineraryController::class, 'index'])->name('index');

    // STEP 1
    Route::get('/form1', [ItineraryController::class, 'create'])->name('form1');
    Route::post('/form1', [ItineraryController::class, 'storeForm1'])->name('storeForm1');

    // STEP 2
    Route::get('/form2', [ItineraryController::class, 'form2'])->name('form2');
    Route::post('/form2', [ItineraryController::class, 'storeForm2'])->name('storeForm2');

    // STEP 3 – fill days (kota & tanggal)
    Route::get('/{itinerary}/fill-days', [ItineraryController::class, 'fillDays'])->name('fill-days');
    Route::post('/{itinerary}/save-days', [ItineraryController::class, 'saveDays'])->name('save-days');

    // STEP 4 – NEW: isi semua kegiatan untuk semua hari (TANPA {day})
    Route::get('/{itinerary}/fill-items', [ItineraryController::class, 'fillItems'])->name('fill-items');
    Route::post('/{itinerary}/save-items', [ItineraryController::class, 'saveItems'])->name('save-items');

    // KONFIRMASI
    Route::get('/{itinerary}/confirm', [ItineraryController::class, 'confirm'])->name('confirm');
    Route::post('/{itinerary}/finalize', [ItineraryController::class, 'finalize'])->name('finalize');

    // EDIT / UPDATE / SHOW / DELETE
    Route::get('/{itinerary}/edit', [ItineraryController::class, 'edit'])->name('edit');
    Route::put('/{itinerary}', [ItineraryController::class, 'update'])->name('update');
    Route::delete('/{itinerary}', [ItineraryController::class, 'destroy'])->name('destroy');

    Route::get('/{itinerary}', [ItineraryController::class, 'show'])->name('show');
});


        // --------------------------------------------------------------------
        // KEHADIRAN
        // --------------------------------------------------------------------
        Route::get('/attendances', [AdminAttendanceController::class, 'index'])
            ->name('attendances.index');
        Route::get('/jamaah', [AdminJamaahController::class, 'index'])
            ->name('jamaah.index');

    }); // END ADMIN GROUP

}); // END AUTH GROUP
