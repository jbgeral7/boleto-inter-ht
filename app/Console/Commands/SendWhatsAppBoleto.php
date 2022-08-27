<?php

namespace App\Console\Commands;

use App\Http\Controllers\Backend\WhatsAppController;
use Illuminate\Console\Command;
use App\Interfaces\BoletoInterface;
use App\Models\Boleto;
use Illuminate\Support\Facades\Log;

class SendWhatsAppBoleto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ln:send-whatsapp {boletos_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia boletos por WhatsApp';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(BoletoInterface $boleto, WhatsAppController $whatsApp)
    {
        parent::__construct();
        $this->boleto = $boleto;
        $this->whatsApp = $whatsApp;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $boletos_id = $this->argument("boletos_id");
        $prefixLog = "WhatsApp send - ";

        $success_ids = [];
        $failed_ids = [];
        foreach($boletos_id as $key => $id){
            $response = $this->whatsApp->sendMessageBeforeBoleto($id, $key);
            if($response->getStatusCode() == 200 || $response->getStatusCode() == 201){
                $success_ids[] = $id;
            }else {
                $failed_ids[] = $id;
            }

            sleep(2);
        }

        if($failed_ids){
            $this->logs("{$prefixLog} falha ao enviar boleto, alertando via WhatsApp " . env('NOTIFY_SEND_BOLETO_WHATSAPP'));
            $this->whatsApp->notifySendBoletoWhatsApp($failed_ids);
        }

        if($success_ids){
            $this->logs("{$prefixLog} Boletos enviados com sucesso, alertando via WhatsApp " . env('NOTIFY_SEND_BOLETO_WHATSAPP'));
            $this->whatsApp->notifySendBoletoWhatsApp($success_ids, true);
        }

        $this->logs("{$prefixLog} finalizado envios pelo WhatsApp \n\n");
    }

    public function logs($message){
        Log::build([
            'driver' => 'daily',
            'path' => storage_path('logs/gerar-boleto/boleto.log'),
        ])->info($message);
    }
}
