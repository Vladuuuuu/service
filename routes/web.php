<?php

use App\Http\Controllers\ClientCarController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServicePanelController;
use Illuminate\Support\Facades\Route;

// === RUTE PUBLICE ===
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');

// === RUTE CLIENT (autentificat) ===
Route::middleware('auth')->group(function () {
    // Dashboard client
    Route::get('/client/dashboard', [ClientController::class, 'dashboard'])->name('client.dashboard');
    Route::get('/client/interventions', [ClientController::class, 'interventions'])->name('client.interventions');
    Route::get('/client/invoices', [ClientController::class, 'invoices'])->name('client.invoices');
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->role === 'client') {
            return redirect()->route('client.dashboard');
        }
        if ($user->role === 'service') {
            return redirect()->route('service.dashboard');
        }
        return redirect('/admin');
    })->name('dashboard');

    // === PANEL SERVICE ===
    Route::prefix('service')->middleware('auth')->group(function () {
        Route::get('/dashboard', [ServicePanelController::class, 'dashboard'])->name('service.dashboard');
        Route::get('/interventions', [ServicePanelController::class, 'interventions'])->name('service.interventions');
        Route::get('/interventions/{intervention}', [ServicePanelController::class, 'showIntervention'])->name('service.interventions.show');
        Route::patch('/interventions/{intervention}/status', [ServicePanelController::class, 'updateStatus'])->name('service.interventions.status');
        Route::patch('/interventions/{intervention}', [ServicePanelController::class, 'updateIntervention'])->name('service.interventions.update');
        Route::post('/interventions/{intervention}/invoice', [ServicePanelController::class, 'createInvoice'])->name('service.interventions.invoice');
        Route::post('/interventions/{intervention}/deviz', [ServicePanelController::class, 'sendDeviz'])->name('service.interventions.deviz');
        Route::get('/settings', [ServicePanelController::class, 'settings'])->name('service.settings');
        Route::patch('/settings', [ServicePanelController::class, 'updateSettings'])->name('service.settings.update');
        Route::get('/analytics', [ServicePanelController::class, 'analytics'])->name('service.analytics');
    });

    // CRUD mașini client
    Route::resource('client/cars', ClientCarController::class)->names([
        'index' => 'client.cars.index',
        'create' => 'client.cars.create',
        'store' => 'client.cars.store',
        'show' => 'client.cars.show',
        'edit' => 'client.cars.edit',
        'update' => 'client.cars.update',
        'destroy' => 'client.cars.destroy',
    ]);

    // Deviz intervenție (client)
    Route::post('/client/interventions/{intervention}/deviz/approve', [ClientController::class, 'approveDeviz'])->name('client.interventions.deviz.approve');
    Route::post('/client/interventions/{intervention}/deviz/reject', [ClientController::class, 'rejectDeviz'])->name('client.interventions.deviz.reject');

    // Programare la service
    Route::post('/services/{service}/book', [ServiceController::class, 'book'])->name('services.book');

    // Factură PDF
    Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('invoices.pdf');

    // Istoric mașină PDF
    Route::get('/client/cars/{car}/history-pdf', [ClientCarController::class, 'historyPdf'])->name('client.cars.history');

    // Profil utilizator (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
