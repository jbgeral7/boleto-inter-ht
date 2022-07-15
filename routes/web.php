<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\HomeController;
use App\Http\Controllers\Backend\BoletoController;
use App\Http\Controllers\Backend\ServiceController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\WhatsAppController;

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
    Route::group(['prefix' => 'painel', 'namespace' => 'App\Http\Controllers\Backend', 'middleware' => 'auth', 'as' => 'backend.'], function()
	{
        Route::get('/', [HomeController::class, 'index'])->name('index');
        Route::get('/get-saldo',  [HomeController::class, 'getSaldo'])->name('get-saldo');
        Route::get('/limpar-cache', [HomeController::class, 'cacheClear'])->name('cache-clear');
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
            Route::get('cancelar/{id}', [BoletoController::class, 'cancel'])->name('cancel');
            Route::get('enviar-email/{id}', [BoletoController::class, 'sendEmail'])->name('send-email');
            Route::get('enviar-email/avulse/{id}', [BoletoController::class, 'sendAvulseEmail'])->name('send-avulse-email');
            Route::get('download-pdf/{id}', [BoletoController::class, 'downloadPdf'])->name('download');
            Route::put('editar/{id}', [BoletoController::class, 'update'])->name('update');
        });

        Route::group(['prefix' => 'whatsapp', 'as' => 'whatsapp.'], function(){
            // Start WhatsApp
            Route::get('/', [WhatsAppController::class, 'index'])->name('index');
            Route::get('close-session', [WhatsAppController::class, 'closeSession'])->name('close.session');
            Route::get('qrcode-login-update', [WhatsAppController::class, 'qrcodeUpdate']);
            Route::get('check-status', [WhatsAppController::class, 'getStatusConnection'])->name('whats.check.status');
            Route::get('send/avulse/whatsapp/{id}', [WhatsAppController::class, 'sendAvulse'])->name('send.avulse');
            // End WhatsApp
        });

    });
});


Auth::routes(['register' => false, 'reset' => false]);

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
