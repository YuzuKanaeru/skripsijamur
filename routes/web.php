<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Redirect guests to the login page. Prefer the named 'login' route (Breeze),
    // fall back to our plain login view route if 'login' is not registered.
    if (Route::has('login')) {
        return redirect()->route('login');
    }
    return redirect()->route('login.plain');
});

// simple plain login (fallback)
Route::get('/login-plain', function(){
    return view('auth.login_plain');
})->name('login.plain');

use App\Models\Penyakit;
use App\Models\Kriteria;
use App\Models\DataJamur;

Route::get('/dashboard', function () {
    $user = auth()->user();
    $counts = [
        'penyakit' => Penyakit::count(),
        'kriteria' => Kriteria::count(),
        'riwayat' => ($user && ($user->role ?? '') === 'admin') ? DataJamur::count() : DataJamur::where('user_id', $user?->id ?? 0)->count(),
    ];
    return view('dashboard', compact('counts'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth','is_admin'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin CRUD for SAW system
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('kriteria', App\Http\Controllers\Admin\KriteriaController::class);
        Route::resource('sub-kriteria', App\Http\Controllers\Admin\SubKriteriaController::class);
        Route::resource('penyakit', App\Http\Controllers\Admin\PenyakitController::class);
        Route::get('hasil', [App\Http\Controllers\Admin\HasilSawController::class, 'index'])->name('hasil.index');
        // mapping penyakit -> sub_kriteria
        Route::get('penyakit/{penyakit}/mapping', [App\Http\Controllers\Admin\PenyakitMappingController::class, 'edit'])->name('penyakit.mapping.edit');
        Route::put('penyakit/{penyakit}/mapping', [App\Http\Controllers\Admin\PenyakitMappingController::class, 'update'])->name('penyakit.mapping.update');
        // admin input nilai (numeric) untuk penyakit -> akan dipetakan ke sub_kriteria
        Route::get('penyakit/{penyakit}/nilai', [App\Http\Controllers\Admin\PenyakitController::class, 'editNilai'])->name('penyakit.nilai.edit');
        Route::post('penyakit/{penyakit}/nilai', [App\Http\Controllers\Admin\PenyakitController::class, 'updateNilai'])->name('penyakit.nilai.update');
    });
});

// Diagnosis routes (user) — require authentication
Route::middleware('auth')->group(function () {
    Route::get('/diagnose', [App\Http\Controllers\DataJamurController::class, 'index'])->name('diagnose.index');
    Route::get('/diagnose/create', [App\Http\Controllers\DataJamurController::class, 'create'])->name('diagnose.create');
    Route::post('/diagnose', [App\Http\Controllers\DataJamurController::class, 'store'])->name('diagnose.store');
    Route::get('/diagnose/{dataJamur}/print', [App\Http\Controllers\DataJamurController::class, 'print'])->name('diagnose.print');
    Route::delete('/diagnose/{dataJamur}', [App\Http\Controllers\DataJamurController::class, 'destroy'])->name('diagnose.destroy');
    Route::get('/diagnose/{dataJamur}', [App\Http\Controllers\DataJamurController::class, 'show'])->name('diagnose.show');
});

// Public list of diseases
Route::get('/penyakit', [App\Http\Controllers\PenyakitController::class, 'index'])->name('penyakit.index');
Route::get('/penyakit/{penyakit}', [App\Http\Controllers\PenyakitController::class, 'show'])->name('penyakit.show');

require __DIR__.'/auth.php';
