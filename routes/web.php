<?php

use App\Http\Controllers\ArquivoController;
use App\Http\Controllers\ClienteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    // Rota do dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
    Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
    Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
    // Route::get('/clientes/{cliente}', [ClienteController::class, 'show'])->name('clientes.show');
    Route::get('/clientes/{cliente}/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
    Route::put('/clientes/{cliente}', [ClienteController::class, 'update'])->name('clientes.update');
    Route::delete('/clientes/{cliente}', [ClienteController::class, 'destroy'])->name('clientes.destroy');

    
    // Rotas para arquivos
    Route::get('/arquivos', [ArquivoController::class, 'index'])->name('arquivos.index');
    Route::get('/arquivos/create', [ArquivoController::class, 'create'])->name('arquivos.create');
    Route::post('/arquivos', [ArquivoController::class, 'store'])->name('arquivos.store');
    Route::get('/arquivos/{arquivo}', [ArquivoController::class, 'show'])->name('arquivos.show');
    Route::put('/arquivos/{arquivo}/edit', [ArquivoController::class, 'edit'])->name('arquivos.edit');
    Route::put('/arquivos/{arquivo}', [ArquivoController::class, 'update'])->name('arquivos.update');
    Route::delete('/arquivos/{arquivo}', [ArquivoController::class, 'destroy'])->name('arquivos.destroy');
});


require __DIR__.'/auth.php';
