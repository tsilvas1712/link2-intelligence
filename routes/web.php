<?php

use App\Livewire\Dashboard;
use App\Livewire\Welcome;
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

Route::get('/', Dashboard::class)->name('dashboard');
Route::get('/metas', \App\Livewire\Metas\Dashboard::class)->name('metas');
Route::prefix('/vendedores')->name('vendedores.')->group(function () {
    Route::get('', \App\Livewire\Vendedores\Dashbboard::class)->name('dashboard');
});
Route::prefix('/filiais')->name('filiais.')->group(function () {
    Route::get('', \App\Livewire\Filiais\Dashboard::class)->name('dashboard');
});
