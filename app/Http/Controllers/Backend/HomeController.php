<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Services\InterService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Repositories\BoletoRepository;
use App\Repositories\ServiceRepository;
use App\Repositories\AbstractRepository;
use App\Repositories\CustomerRepository;

class HomeController extends Controller
{

    public function __construct(CustomerRepository $customer, ServiceRepository $service, BoletoRepository $boleto, InterService $inter){
        $this->customer = $customer;
        $this->service = $service;
        $this->boleto = $boleto;
        $this->inter = $inter;
    }
    
    public function index(){
        $qtdCustomer = $this->customer->get()->count();
        $qtdService = $this->service->get()->count();
        $qtdBoleto = $this->boleto->get()->count();
        $saldo = $this->getSaldoCache()->disponivel;
        
        return view('Backend.Home.index', compact('qtdCustomer', 'qtdService', 'qtdBoleto', 'saldo'));
    }

    protected function getSaldoCache(){
        if(Cache::has('saldo-conta-inter')){
            return Cache::get('saldo-conta-inter');
        }

        $response = $this->getSaldo();
        Cache::put('saldo-conta-inter', $response, 86400);

        return Cache::get('saldo-conta-inter');
    }

    protected function getSaldo(){
        $decode = $this->inter->getSaldo();
        Cache::put('saldo-conta-inter', json_decode($decode), 86400);
        return json_decode($decode);
    }

    public function cacheClear(){
        \Artisan::call('cache:clear');

        return redirect()->back()
            ->with(['message' => 'Cache limpo com sucesso', 'alert-type' => 'success']);
    }
}
