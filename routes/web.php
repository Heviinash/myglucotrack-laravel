<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\BloodSugarController;
use App\Http\Controllers\GodController;
use App\Http\Controllers\AdminController;
use App\Models\Patient;
use App\Models\BloodSugarLevel;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ── Welcome ───────────────────────────────────────────────────────
Route::get('/', fn() => view('welcome'));

// ── Dashboard ─────────────────────────────────────────────────────
Route::get('/dashboard', function () {
    $tid = Auth::user()->tenant_id;
    $totalPatients = Patient::where('tenant_id', $tid)->count();
    $totalRecords  = BloodSugarLevel::where('tenant_id', $tid)->count();
    $highReadings  = BloodSugarLevel::where('tenant_id', $tid)->where('blood_sugar_level', '>=', 7)->count();
    $todayReadings = BloodSugarLevel::where('tenant_id', $tid)->whereDate('measurement_date', now())->count();
    $recentRecords = BloodSugarLevel::with('patient')->where('tenant_id', $tid)->latest()->take(5)->get();
    return view('dashboard', compact('totalPatients','totalRecords','highReadings','todayReadings','recentRecords'));
})->middleware(['auth', 'verified', 'tenant'])->name('dashboard');


// ── Profile ───────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
});


// ── Patients & Blood Sugar ────────────────────────────────────────
Route::middleware(['auth', 'verified', 'tenant'])->group(function () {
    Route::resource('patients', PatientController::class);
    Route::resource('blood-sugar', BloodSugarController::class);
});


// ── Admin Panel ───────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'tenant', 'role:Admin'])
    ->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users',                          [AdminController::class, 'index'])->name('users');
        Route::get('/users/create',                   [AdminController::class, 'create'])->name('users.create');
        Route::post('/users',                         [AdminController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit',              [AdminController::class, 'edit'])->name('users.edit');
        Route::patch('/users/{user}',                 [AdminController::class, 'update'])->name('users.update');
        Route::post('/users/{user}/toggle',           [AdminController::class, 'toggle'])->name('users.toggle');
        Route::delete('/users/{user}',                [AdminController::class, 'destroy'])->name('users.destroy');
        Route::get('/users/{user}/reset-password',    [AdminController::class, 'resetPasswordForm'])->name('users.reset-password');
        Route::post('/users/{user}/reset-password',   [AdminController::class, 'resetPassword'])->name('users.reset-password.update');
    });


// ── God Panel ─────────────────────────────────────────────────────
Route::middleware(['auth', 'role:System God'])
    ->prefix('god')->name('god.')->group(function () {
        Route::get('/dashboard',                        [GodController::class, 'dashboard'])->name('dashboard');
        Route::get('/admins',                           [GodController::class, 'admins'])->name('admins');
        Route::get('/admins/create',                    [GodController::class, 'createAdmin'])->name('admins.create');
        Route::post('/admins',                          [GodController::class, 'storeAdmin'])->name('admins.store');
        Route::get('/admins/{user}/edit',               [GodController::class, 'editAdmin'])->name('admins.edit');
        Route::patch('/admins/{user}',                  [GodController::class, 'updateAdmin'])->name('admins.update');
        Route::post('/admins/{user}/toggle',            [GodController::class, 'toggleAdmin'])->name('admins.toggle');
        Route::delete('/admins/{user}',                 [GodController::class, 'destroyAdmin'])->name('admins.destroy');
        Route::get('/admins/{user}/reset-password',     [GodController::class, 'resetPasswordForm'])->name('admins.reset-password');
        Route::post('/admins/{user}/reset-password',    [GodController::class, 'resetPassword'])->name('admins.reset-password.update');
        Route::get('/users',                            [GodController::class, 'users'])->name('users');
        Route::get('/audit-logs',                        [GodController::class, 'auditLogs'])->name('audit-logs');
    });


require __DIR__.'/auth.php';
