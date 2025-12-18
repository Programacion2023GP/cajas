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

Route::get('/ajuste/{id}', function ($id) {
    try {
        // $before = DB::select("SHOW TABLE STATUS LIKE 'turnos'")[0]->Auto_increment;

        DB::statement("ALTER TABLE turnos AUTO_INCREMENT = $id");

        // $after = DB::select("SHOW TABLE STATUS LIKE 'turnos'")[0]->Auto_increment;

        // dd([
        //     'antes' => $before,
        //     'despues' => $after
        // ]);

        // ✔ Se ejecutó sin error
    } catch (\Throwable $e) {
        // ❌ Falló
        dd($e->getMessage());
    }
    // return view('auth.login');
})->name('ajuste');

Route::get(
    '/dashboard',
    [DashController::class, 'index']
)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/turnos', [TurnosController::class, 'store'])->name('turnos');
});

require __DIR__ . '/auth.php';
