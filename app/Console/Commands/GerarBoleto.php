<?php

namespace App\Console\Commands;

use App\Interfaces\BoletoInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Interfaces\CustomerInterface;

class GerarBoleto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ln:auto_generate_boleto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gera os boletos automaticamente todo dia 01 para clientes ativos com serviços definidos';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CustomerInterface $customer, BoletoInterface $boleto)
    {
        parent::__construct();
        $this->customer = $customer;
        $this->boleto = $boleto;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $prefixLog = "Gerar boleto -";
        $this->logs("{$prefixLog} Procurando customers ativos e com serviços atrelados");
        $getActiveCustomer = $this->customer->customerActiveWith('services');
        $this->logs("{$prefixLog} Encontrado {$getActiveCustomer->count()} customers ativos \n\n");

        $boletos = [];
        foreach($getActiveCustomer as $customer){
            $total_price = 0;

            $this->logs("{$prefixLog} {$customer->name} tem {$customer->services->count()} serviços ativos");

            foreach($customer->services as $customerService){
                $this->logs("{$prefixLog} {$customerService->name} R$ {$customerService->price}");
                $total_price += number_format($customerService->price, 2, '.', '');
            }

            $this->logs("{$prefixLog} Total dos serviços de {$customer->name} R$ {$total_price}");

            // $data =  $this->generateData($customer, $total_price);
            

            // $this->logs("{$prefixLog} meu número {$data['my_number']} vencimento dia {$data['due_date']}");
            
            // $response = $this->boleto->store($data);

        //    if(!isset($response->original->id)){
        //        $this->logs("{$prefixLog} Falha ao gerar boleto para {$customer->name}");
        //        $this->boleto->failedSendBoleto(['erro' => "Erro ao gerar boleto"]);
        //        continue;
        //     }
        
        //     $boletos[] = $response->original->id;

            $this->logs("{$prefixLog} terminado customer {$customer->name} pegando o próximo \n\n");
            
        }

        $this->logs("{$prefixLog} finalizado a geração de boletos, chamando a tarefa de Download");
        $this->call("ln:downloadBoleto", ["boletos_id" => [0 => 7, 1 => 8, 2 => 666]]);
    }

    public function generateData($customer, $total_price){
        $data['my_number'] = $this->generateMyNumber($customer);
        $data['price_cron'] = $total_price;
        $data['due_date'] = date("Y-m-") . $customer->expiration_day_boleto;
        $data['date_to_pay_after_due_date'] = 0;
        $data['customer_id'] = $customer->id;
        return $data;
    }

    public function generateMyNumber($customer){
       return $customer->fantasy_name ? str_replace(" ", "-", $customer->fantasy_name) . "-". date('H-s-m') : explode(" ", $customer->name)[0] . "-" . date('H-s-m');
    }

    public function logs($message){
        Log::build([
            'driver' => 'daily',
            'path' => storage_path('logs/gerar-boleto/boleto.log'),
        ])->info($message);
    }
}

