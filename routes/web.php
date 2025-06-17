<?php

use App\Livewire\Dashboard;
use App\Livewire\Login;
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
    Route::get('/', \App\Livewire\App\Dashboard::class)->name('dashboard');
    Route::prefix('/detalhes')->name('detalhes.')->group(function () {
        Route::get('/grupos/{id}', \App\Livewire\Detalhamento\Grupos::class)->name('grupos');
    });
    Route::prefix('/vendedores')->name('vendedores.')->group(function () {
        Route::get('', \App\Livewire\Vendedores\Dashboard::class)->name('dashboard');
        Route::get('/{id}', \App\Livewire\Vendedores\Show::class)->name('show');
    });
    Route::prefix('/filial')->name('filial.')->group(function () {
        Route::get('/{id}', \App\Livewire\Filial\Dashboard::class)->name('dashboard');
    });

    Route::prefix('/vendedor')->name('vendedor.')->group(function () {
        Route::get('/{id}', \App\Livewire\Vendedor\Dashboard::class)->name('dashboard');
    });

    Route::prefix('/filiais')->name('filiais.')->group(function () {
        Route::get('', \App\Livewire\Filiais\Dashboard::class)->name('dashboard');
        Route::get('/{id}', \App\Livewire\Filiais\Filial::class)->name('show');
        // Route::get('relatorio/{id}', \App\Livewire\Filiais\show::class)->name('relatorio');
    });

    Route::prefix('/admin')->name('admin.')->group(function () {
        Route::get('/datasys', \App\Livewire\Admin\Datasys\Api::class)->name('datasys.api');
        Route::get('/filiais', \App\Livewire\Admin\Filiais\Main::class)->name('filiais');
        Route::get('/filiais/{id}', \App\Livewire\Admin\Filiais\Show::class)->name('filiais.show');

        Route::get('/planos', \App\Livewire\Admin\Planos::class)->name('planos');

        Route::get('/vendedores', \App\Livewire\Admin\Vendedores\Main::class)->name('vendedores');
        Route::get('/vendedores/{id}', \App\Livewire\Admin\Vendedores\Show::class)->name('vendedores.show');

        Route::get('/usuarios', \App\Livewire\Admin\Usuarios\Main::class)->name('usuarios');
        Route::get('/usuarios/show/{id?}', \App\Livewire\Admin\Usuarios\Show::class)->name('usuarios.show');

        Route::get('', \App\Livewire\Admin\Dashboard::class)->name('dashboard');
        Route::get('/grupos', \App\Livewire\Admin\Group\Main::class)->name('groups');
        Route::get('/grupos/novo', \App\Livewire\Admin\Group\Criar::class)->name('groups.criar');
        Route::get('/grupos/editar/{id}', \App\Livewire\Admin\Group\Criar::class)->name('groups.editar');
    });
});
