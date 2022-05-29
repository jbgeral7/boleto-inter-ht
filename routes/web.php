<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\ServiceController;
use App\Http\Controllers\Backend\BoletoController;

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




Route::group(['middleware' => ['web']], function(){
    Route::group(['prefix' => 'painel', 'namespace' => 'App\Http\Controllers\Backend', 'middleware' => 'web', 'as' => 'backend.'], function()
	{
        Route::group(['prefix' => 'cliente', 'as' => 'customer.'], function(){
            Route::get('listar', [CustomerController::class, 'index'])->name('index');
            Route::get('adicionar', [CustomerController::class, 'create'])->name('create');
            Route::post('adicionar', [CustomerController::class, 'store'])->name('store');
            Route::get('editar/{id}', [CustomerController::class, 'edit'])->name('edit');
            Route::put('editar/{id}', [CustomerController::class, 'update'])->name('update');
            Route::get('boletos/listar/{id}', [CustomerController::class, 'getBoletos'])->name('boletos');
        });

        Route::group(['prefix' => 'servico', 'as' => 'service.'], function(){
            Route::get('listar', [ServiceController::class, 'index'])->name('index');
            Route::get('adicionar', [ServiceController::class, 'create'])->name('create');
            Route::post('adicionar', [ServiceController::class, 'store'])->name('store');
            Route::get('editar/{id}', [ServiceController::class, 'edit'])->name('edit');
            Route::put('editar/{id}', [ServiceController::class, 'update'])->name('update');
        });
        
        Route::group(['prefix' => 'boleto', 'as' => 'boleto.'], function(){
            Route::get('listar/{cliente?}/{customer_id?}', [BoletoController::class, 'index'])->name('index');
            Route::get('gerar', [BoletoController::class, 'create'])->name('create');
            Route::post('gerar', [BoletoController::class, 'store'])->name('store');
            Route::post('auto-gerar', [BoletoController::class, 'autoGenerate'])->name('auto-generate');
            Route::get('status', [BoletoController::class, 'status'])->name('status');
            Route::get('enviar-email/{id}', [BoletoController::class, 'sendEmail'])->name('send-email');
            Route::get('download-pdf/{id}', [BoletoController::class, 'downloadPdf'])->name('download');
        });

    });
});


Auth::routes(['register' => false, 'reset' => false]);


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
