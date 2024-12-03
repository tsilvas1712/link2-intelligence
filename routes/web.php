<?php

use App\Livewire\Dashboard;
use App\Livewire\Login;
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


Route::get('/login', Login::class)->name('login');

Route::get('/logout', function () {
    \Illuminate\Support\Facades\Auth::logout();

    session()->invalidate();
    session()->regenerateToken();

    return redirect(route('login'));
})->name('logout');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dash', Dashboard::class)->name('dash');
    Route::get('/', \App\Livewire\Metas\Dashboard::class)->name('dashboard');
    Route::prefix('/vendedores')->name('vendedores.')->group(function () {
        Route::get('', \App\Livewire\Vendedores\Dashboard::class)->name('dashboard');
        Route::get('/{id}', \App\Livewire\Vendedores\Show::class)->name('show');
    });
    Route::prefix('/filiais')->name('filiais.')->group(function () {
        Route::get('', \App\Livewire\Filiais\Dashboard::class)->name('dashboard');
        Route::get('/{id}', \App\Livewire\Filiais\Show::class)->name('show');
    });
});
