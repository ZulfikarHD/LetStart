<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return Inertia::render('Home', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'isAuthenticated' => Auth::check()
    ]);
});

Route::get('/venue-listing', function () {
    return Inertia::render('VenueListing', [
        'isAuthenticated' => Auth::check()
    ]);
});

Route::get('/sports-category', function () {
    return Inertia::render('SportsCategory', [
        'isAuthenticated' => Auth::check()
    ]);
});

Route::get('/venues/{id}', function ($id) {
    return Inertia::render('VenueDetail', [
        'id' => $id,
        'auth' => [
            'user' => Auth::user()
        ]
    ]);
})->name('venues.show');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
