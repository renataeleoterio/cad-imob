<?php

use App\Http\Controllers\ImovelController;
use App\Http\Controllers\PessoaController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Pessoa;
use Illuminate\Http\Request;

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

Route::resource('pessoas', PessoaController::class);

Route::get('/', function () {
    return Inertia::render('Dashboard');
})->name('Home');


Route::resource('imoveis', ImovelController::class);

Route::get('/usuarios', function () {
    return Inertia::render('Usuarios/Index');
})->name('usuarios.index');

Route::get('/configuracoes', function () {
    return Inertia::render('Configuracoes/Index');
})->name('configuracoes.index');

/*Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/', function () {
    return Inertia::render('Index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});*/

require __DIR__ . '/auth.php';
