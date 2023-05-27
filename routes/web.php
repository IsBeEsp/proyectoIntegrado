<?php

use App\Http\Livewire\Calendario;
use App\Http\Livewire\ShowLibros;
use App\Http\Livewire\ShowPagina;
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
    return view('welcome');
})->name('welcome');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {

    Route::get('/books', ShowLibros::class)->name('libros.show');

    Route::get('/page', ShowPagina::class)->name('pagina.show');

    Route::get('/calendar', Calendario::class)->name('calendario.show');

});