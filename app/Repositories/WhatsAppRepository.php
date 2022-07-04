<?php

namespace App\Repositories;

use App\Models\Boleto;
use App\Models\WhatsApp;
use App\Services\LnService;
use Illuminate\Support\Facades\Log;
use App\Interfaces\WhatsAppInterface;
use Illuminate\Support\Facades\Session;

class WhatsAppRepository extends AbstractRepository implements WhatsAppInterface {

    protected $model = WhatsApp::class;
    protected $abs = AbstractRepository::class;

    public function __construct(LnService $lnService)
    {
        parent::__construct();
        $this->lnService = $lnService;
        $this->prefixLog = "WhatsApp send - ";
        $this->url = env('WHATSAPP_BASE_URL');
        $this->key = env('WHATSAPP_SECRET_KEY');
        $this->session = env('WHATSAPP_SESSION');
    }

    private function headers(){
        return ['Authorization' => 'Bearer ' . $this->getInfoWhats()->whatsapp_token];
    }

    private function getInfoWhats(){
        return $this->abs::first();
    }

    public function login(){
        $whats = $this->getInfoWhats();

        try {
            // Caso não tenha seção e nem token, inicia-se
            if (!isset($whats->whatsapp_token) && !isset($whats->whatsapp_session)) {
                $url = $this->url . $this->session . '/' . $this->key . '/generate-token';

                $response = $this->lnService->post($url);
                $response = json_decode($response->getBody()->getContents(), true);
                if ($response['status'] == 'success') {
                    $data = [
                        'whatsapp_token' => $response['token'],
                        'whatsapp_session' => $response['session'],
                    ];

                    $this->abs::store($data);
                }

            }elseif($whats->whatsapp_token && $whats->whatsapp_session && !$whats->whatsapp_init) {
                // Tem sessão e token, mas não está iniciado
                $url = $this->url . $this->session . '/start-session';
                $response = $this->lnService->post($url, $this->headers());
                $response = json_decode($response->getBody()->getContents(), true);
                if(strtolower($response['status']) == 'connected') {
                    $data = ['whatsapp_init' => true];
                    $this->abs::update($data, $whats->id);
                } 
            }
            elseif($whats->whatsapp_token && $whats->whatsapp_session && $whats->whatsapp_init) {
                $url = $this->url . $this->session . '/status-session';
                $response = $this->lnService->get($url, $this->headers());
                $response = json_decode($response->getBody()->getContents(), true);
            }
            return $response;
        }catch(\Exception $e){
            dd($e->getMessage());
        }
    }

    public function qrcodeUpdate(){
        $url = $this->url . $this->session . '/status-session';
        $response = $this->lnService->get($url, $this->headers());
        return $response;
    }

    public function closeSession(){
        $whats = $this->getInfoWhats();

        if($whats->whatsapp_token && $whats->whatsapp_session){
            $url = $this->url . $this->session . '/close-session';
            $response = $this->lnService->post($url, $this->headers());
            if($response['status'] == true){
                $this->abs::delete($whats->id);
                Session::flush();
                return redirect('/login');
            }
        }

        return redirect()->route('backend.whatsapp.index')
        ->with(['message' => 'Ocorreu um erro ao tentar encerrar a sessão. Por favor, tente novamente em alguns segundos.', 'alert-type' => 'error']);
    }

    public function getStatusConnection(){
        $whats = $this->getInfoWhats();
        if(!empty($whats->whatsapp_token) && !empty($whats->whatsapp_session)){
            $url = $this->url . $this->session . '/check-connection-session';
            $response = $this->lnService->get($url, $this->headers());
            return  json_decode($response->getBody()->getContents(),true);
        }else {
            return json_encode(['status' => false, 'message' => 'Disconnected']);
        }
    }

    public function verifyPermissionSendBoleto($find, $type){
        return $find->customer->$type == 1 ? $find : false;
    }

    public function sendMessageBeforeBoleto($boleto_id){

        $find = Boleto::where('id', $boleto_id)->with('customer')->firstOrFail();
        
        $permission = $this->verifyPermissionSendBoleto($find, 'whatsapp_notify');

        if(!$permission){
            $this->logs("{$this->prefixLog} boleto id {$boleto_id} não enviado. Cliente configurado para não receber boletos via WhatsApp. " . $find->customer . "\n\n");
            return response("O cliente está configurado para não receber boletos por WhatsApp, verifique o cadastro do cliente", 401);
        }

        $this->logs("{$this->prefixLog} Iniciando o envio de mensagem de cobrança do boleto id {$boleto_id} para o cliente " . $find->customer);

        $value = convertBRL($find->price);
        $url = $this->url . $this->session . '/send-message';
        $headers = ['Authorization' => 'Bearer ' . '$2b$10$Mr_FVJ06JH7Fd7HkV_6TlOrPI8AbXI3mlmCXvpaJ4UkqCMxSN.5yC'];
        $due_date = date("d/m/Y", strtotime($find->due_date));
        $string = "*Seu boleto chegou!* \n\n
        *Olá, {$find->customer->name} - {$find->customer->fantasy_name}*
        O seu boleto no valor de *R$ {$value} com vencimento no dia {$due_date}* está em anexo e disponível para pagamento. 
        Lembramos que caso não seja quitado até o *dia {$due_date}, será necessário solicitar a segunda via, que poderá ter custos adicionais*. 
        *Linha digitável: {$find->digitable_line}*
        *Chave pix: " . env('CHAVE_PIX') . "*
        *A sua nota fiscal chegará em breve por email.*
        *Para mais detalhes, confira seu e-mail.*
        *Mensagem enviada automaticamente em* " . date('d/m/Y \à\\s H:i') . ".";

        $words = preg_replace('/\s\s+/', "\n\n", $string);

        $data = [
            "phone" => $find->customer->whatsapp,
            "message" => $words,
            "isGroup" => false
        ];

        $sendLn = $this->lnService->post($url, $headers, $data);
        $response = json_decode($sendLn->getBody()->getContents(),true);

        if(strtolower($response['status']) == "success"){
            $this->logs("{$this->prefixLog} Mensagem de cobrança enviada com sucesso para o cliente. " . $find->customer);
           return $this->sendBoleto($find->customer, $find);
        }

    }

    public function sendBoleto($customer, $boleto){
        $this->logs("{$this->prefixLog} Iniciando o envio de boleto");

        $url = $this->url . $this->session . '/send-file-base64';
        $headers = ['Authorization' => 'Bearer ' . '$2b$10$Mr_FVJ06JH7Fd7HkV_6TlOrPI8AbXI3mlmCXvpaJ4UkqCMxSN.5yC'];

        $archive = base_path() . "/storage/app/" . $boleto->path . $boleto->boleto;

        $pdf_base64_handler = fopen($archive,'r');
        $pdf_content = fread ($pdf_base64_handler,filesize($archive));
        fclose ($pdf_base64_handler);

        $data = [
            "phone" => $customer->whatsapp,
            "base64" => "data:application/pdf;base64," . base64_encode($pdf_content),
            "filename" => "{$this->nameBoleto($customer)}.pdf",
            "isGroup" => false
        ];

        $response = $this->lnService->post($url, $headers, $data);

        if(strtolower($response['status']) == "success"){
            $this->model = Boleto::class;
            $this->abs::update(["whatsapp_notify_send" => date("Y-m-d H:i:s")], $boleto->id);
            $this->logs("{$this->prefixLog} boleto enviado com sucesso para o cliente " . $customer . "\n\n");
            return $response;
        }else {
            $this->logs("{$this->prefixLog} ocorreu um erro ao enviar o boleto para o cliente " . $customer . "\n\n");
        }
    }

    public function nameBoleto($customer){
        if(!empty($customer->fantasy_name)){
            return preg_replace('/[^a-z0-9]/i', '_', $customer->fantasy_name);
        }

        return preg_replace('/[^a-z0-9]/i', '_', $customer->name);
    }

    public function notifySendBoletoWhatsApp($ids, $success){
            $boletos = Boleto::whereIn('id', $ids)->with('customer')->get();
            
            $name = [];
            $ids = [];
            foreach($boletos as $customer){
                $name[] = $customer->customer->name . " - " .  $customer->customer->fantasy_name;
                $ids[] = $customer->customer->id;
            }

            $sendName = implode(" \n", $name);

            $title = $success ? "*Boleto enviado com sucesso para os clientes*" : "*Falha ao enviar boleto para os clientes*" ;

            $content = $title . "\n\n" . $sendName;

            $url = $this->url . $this->session . '/send-message';
            $headers = ['Authorization' => 'Bearer ' . '$2b$10$Mr_FVJ06JH7Fd7HkV_6TlOrPI8AbXI3mlmCXvpaJ4UkqCMxSN.5yC'];
            
            $data = [
                "phone" => env("NOTIFY_SEND_BOLETO_WHATSAPP"),
                "message" => $content,
                "isGroup" => false
            ];
    
            $this->lnService->post($url, $headers, $data);

            $this->logs("{$this->prefixLog} alertado via WhatsApp,  " . $title . " cliente id: " . implode(", ", $ids) . "\n");

            return;
        }

    public function logs($message){
        Log::build([
            'driver' => 'daily',
            'path' => storage_path('logs/gerar-boleto/boleto.log'),
        ])->info($message);
    }
}
