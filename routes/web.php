<?php

use App\Http\Controllers\Admin\GymCoordinateController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\GymController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WodController;
use App\Http\Controllers\WodResultController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/gyms', [GymController::class, 'index'])->name('gyms.index');
Route::get('/gyms/map', [GymController::class, 'map'])->name('gyms.map');
Route::get('/gyms/{gym}', [GymController::class, 'show'])->name('gyms.show');

Route::get('/movements', [MovementController::class, 'index'])->name('movements.index');
Route::get('/movements/{movement}', [MovementController::class, 'show'])->name('movements.show');

Route::get('/wods', [WodController::class, 'index'])->name('wods.index');
Route::get('/wods/{wod}', [WodController::class, 'show'])->name('wods.show');

Route::middleware('auth')->group(function () {
    Route::get('/my-wods', [ProfileController::class, 'index'])->name('profile.wods');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::post('/wods/{wod}/results', [WodResultController::class, 'store'])
        ->name('wods.results.store');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::post('/locale', [LocaleController::class, 'update'])->name('locale.update');

Route::middleware('auth:moonshine')
    ->prefix(config('moonshine.prefix', 'admin'))
    ->as('moonshine.')
    ->group(function () {
        Route::post('/gyms/{gym}/coordinates', GymCoordinateController::class)
            ->whereNumber('gym')
            ->name('gyms.coordinates');
    });
