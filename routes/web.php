<?php

use App\Http\Controllers\ClienteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\MembresiaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\ReporteController;

Route::redirect('/', '/login')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');
     Route::resource('clientes', ClienteController::class);
     Route::get('planes', [PlanController::class, 'index'])->name('planes.index');
Route::get('planes/create', [PlanController::class, 'create'])->name('planes.create');
Route::post('planes', [PlanController::class, 'store'])->name('planes.store');
Route::get('planes/{plan}/edit', [PlanController::class, 'edit'])->name('planes.edit');
Route::put('planes/{plan}', [PlanController::class, 'update'])->name('planes.update');
Route::get('membresias', [MembresiaController::class, 'index'])->name('membresias.index');
Route::get('membresias/create', [MembresiaController::class, 'create'])->name('membresias.create');
Route::post('membresias', [MembresiaController::class, 'store'])->name('membresias.store');

Route::get('pagos', [PagoController::class, 'index'])->name('pagos.index');
Route::get('pagos/create', [PagoController::class, 'create'])->name('pagos.create');
Route::post('pagos', [PagoController::class, 'store'])->name('pagos.store');

Route::get('asistencias', [AsistenciaController::class, 'index'])->name('asistencias.index');
Route::get('asistencias/create', [AsistenciaController::class, 'create'])->name('asistencias.create');
Route::post('asistencias', [AsistenciaController::class, 'store'])->name('asistencias.store');

Route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');

Route::delete('planes/{plan}', [PlanController::class, 'destroy'])->name('planes.destroy');
});

require __DIR__.'/settings.php';
