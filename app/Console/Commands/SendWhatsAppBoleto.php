<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Interfaces\BoletoInterface;
use Illuminate\Support\Facades\Log;

class SendWhatsAppBoleto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ln:send-whatsapp';

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
    public function __construct(BoletoInterface $boleto)
    {
        parent::__construct();
        $this->boleto = $boleto;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $boletos_id = $this->argument("boletos_id");
        $prefixLog = "WhatsApp envio -";

    }

    public function logs($message){
        Log::build([
            'driver' => 'daily',
            'path' => storage_path('logs/gerar-boleto/boleto.log'),
        ])->info($message);
    }
}
