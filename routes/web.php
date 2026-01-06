<?php

use App\Http\Controllers\DashController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TurnosController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/reset', function () {
    DB::statement("TRUNCATE TABLE turnos;");
})->name('reset');

Route::get('/ajuste/{turno}', [TurnosController::class, 'ajustarTurno'])->name('ajuste');

Route::get(
    '/dashboard',
    [DashController::class, 'index']
)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/turnos', [TurnosController::class, 'storeWhitLetter'])->name('turnos');
    Route::post('/repetir-anuncio', [TurnosController::class, 'repetirAnuncio'])->name('repetir.anuncio');
});

require __DIR__ . '/auth.php';
