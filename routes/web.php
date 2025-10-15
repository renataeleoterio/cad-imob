<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ImovelController;
use App\Http\Controllers\PessoaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Pessoa;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
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


Route::post('/login', [AuthenticatedSessionController::class, 'login']);
Route::post('/logout', [AuthenticatedSessionController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('/', function () {
        return Inertia::render('Dashboard');
    })->name('Home');

    Route::resource('pessoas', PessoaController::class);


    Route::get('/imoveis', [ImovelController::class, 'index'])->name('imoveis.index')->middleware(HandlePrecognitiveRequests::class);
    Route::get('/imoveis/create', [ImovelController::class, 'create'])->name('imoveis.create')->middleware(HandlePrecognitiveRequests::class);
    Route::post('/imoveis', [ImovelController::class, 'store'])->name('imoveis.store')->middleware(HandlePrecognitiveRequests::class);
    Route::get('/imoveis/{imovel}/edit', [ImovelController::class, 'edit'])->name('imoveis.edit');
    Route::put('/imoveis/{imovel}', [ImovelController::class, 'update'])->name('imoveis.update')->middleware(HandlePrecognitiveRequests::class);
    Route::delete('/imoveis/{imovel}', [ImovelController::class, 'destroy'])->name('imoveis.destroy')->middleware(HandlePrecognitiveRequests::class);
    Route::post('imoveis/{imovel}/documentos', [ImovelController::class, 'storeDocumento'])->name('imoveis.documentos.store')->middleware(HandlePrecognitiveRequests::class);
    Route::delete('imoveis/{imovel}/documentos/{documento}', [ImovelController::class, 'destroyDocumento'])->name('imoveis.documentos.destroy')->middleware(HandlePrecognitiveRequests::class);
    Route::get('imoveis/{imovel}/{documento}/download', [ImovelController::class, 'downloadDocumento'])->name('imoveis.documentos.download')->middleware(HandlePrecognitiveRequests::class);
    Route::patch('/imoveis/{imovel}/inativar', [ImovelController::class, 'inativar'])
    ->name('imoveis.inativar');

    Route::get('/configuracoes', function () {
    return Inertia::render('Configuracoes/Index');
    })->name('configuracoes.index');


    Route::get('/users', [UserController::class, 'index'])
        // ->middleware('perfil:T,S') // TI e Sistema podem listar
        ->name('users.index');

    // Route::get('/users/create', [UserController::class, 'create'])
    //     // ->middleware('perfil:T,S')
    //     ->name('users.create');
    
        // Route::post('/users', [UserController::class, 'store'])
        // ->middleware('perfil:T,S')
        // ->name('users.store');

    Route::get('/users/{user}/edit', [UserController::class, 'edit'])
        // ->middleware('perfil:T,S')
        ->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])
        // ->middleware('perfil:T,S')
        ->name('users.update');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

    // Route::delete('/users/{user}', [UserController::class, 'destroy'])
    //     ->middleware('perfil:T') // só TI pode deletar
    //     ->name('users.destroy');
});



/*Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});*/



require __DIR__ . '/auth.php';
