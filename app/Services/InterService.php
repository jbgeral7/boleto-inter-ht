<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class InterService
{
    private $lnService;
    private $url;
    private $client_id;
    private $client_secret;
    private $client_scope;

    public function __construct(LnService $lnService){
        $this->lnService = $lnService;
        $this->url = env('INTER_BASE_URL');
        $this->client_id = env('INTER_CLIENT_ID');
        $this->client_secret = env('INTER_CLIENT_SECRET');
        $this->client_scope = env('INTER_CLIENT_SCOPE');
    }

    private function certKey(){
        return [
            'cert' => [env('INTER_PATH_CRT'), ''],
            'ssl_key' => [env('INTER_PATH_KEY')]
        ];
    }

    private function checkOAuthLogin(){
        if(Cache::has('token_inter_oauth')){
            return Cache::get('token_inter_oauth');
        }
        
        return $this->login();
    }

    private function login(){
        $data = [
            'grant_type' => 'client_credentials',
            'scope' => $this->client_scope,
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret
        ];

        $headers = [
             'Accept' => 'application/json',
             "Content-Type" => "application/x-www-form-urlencoded"
            ];
  
        $response = $this->lnService->post(
            $this->url . 'oauth/v2/token', 
            $headers, 
            $data, 
            $this->certKey(), 
            true
        );

        if($response->getStatusCode() == 200){
            $response = json_decode($response->getBody()->getContents());
            Cache::put('token_inter_oauth', $response->access_token, $response->expires_in - 100);
            return Cache::get('token_inter_oauth');
        }

        return $response->getStatusCode();
        
    }

    public function generatePaymentTicket($customer, $boleto){

        $oauth = $this->checkOAuthLogin();

        // return $this->cancel('00812272294');

        $data = [
            "valorAbatimento" => 0,
                "pagador" => [
                    "cpfCnpj" => $customer->cpf_cnpj,
                    "tipoPessoa" => $customer->type_of_person,
                    "nome" => $customer->name,
                    "endereco" => $customer->address,
                    "numero" => $customer->address_number,
                    "complemento" => $customer->complement,
                    "bairro" => $customer->district,
                    "cidade" => $customer->city,
                    "uf" => $customer->state,
                    "cep" => $customer->zipcode,
                    "email" => $customer->email,
                    "ddd" => substr($customer->phone, 0,2),
                    "telefone" => substr($customer->phone, 2)
                ],
                "multa" => [
                    "codigoMulta" => "NAOTEMMULTA",
                    "multa" => 0,
                    "valor" => 0,
                    "taxa" => 0
                ],
                "mora" => [
                    "codigoMora" => "ISENTO",
                    "taxa" => 0,
                    "valor" => 0
                ],

                "seuNumero" => $boleto['my_number'],
                "valorNominal" => $boleto['price'],
                "dataVencimento" => $boleto['due_date'],
                "numDiasAgenda" => $boleto['date_to_pay_after_due_date']
            ];

        $headers =  [
            'Authorization' => 'Bearer ' . $oauth,
            "Content-Type" => "application/json"
        ];

       $response = $this->lnService->post(
            $this->url . 'cobranca/v2/boletos',
            $headers, 
            $data,
            $this->certKey(), 
            false);

        $prefixLog = "Inter Service gerar boleto -";

        $this->logs("{$prefixLog} Log boleto cliente {$customer->name} {$response->getBody()} \n\n");
            
        return $response;
    }

    public function getPdf($number){

        $headers =  [
            'Authorization' => 'Bearer ' . $this->checkOAuthLogin(),
            "Content-Type" => "application/json"
        ];

        $response = $this->lnService->get(
            $this->url . "cobranca/v2/boletos/{$number}/pdf", 
            $headers,
            $this->certKey()
        );

        if($response->getStatusCode() == 200){
            $body = $response->getBody();
            $status = json_decode($body);
            $path = "boleto/pdf/" . date('Y/m/');
            $name = md5($number . date('D-H:i:s')) . '.pdf';
            Storage::disk('local')->put($path . $name, base64_decode($status->pdf));
            return response()->json(["status" => 200, "path" => $path, "boleto" => $name]);
        }

        return $response;

    }

    public function cancel($number){
        $oauth = $this->checkOAuthLogin();

        $data = ["motivoCancelamento" => "APEDIDODOCLIENTE"];

        $headers =  [
            'Authorization' => 'Bearer ' . $oauth,
            "Content-Type" => "application/json"
        ];

       $response = $this->lnService->post(
            $this->url . "cobranca/v2/boletos/{$number}/cancelar",
            $headers, 
            $data,
            $this->certKey(), 
            false);

            $prefixLog = "cancelar-boleto";

        $this->logs("{$prefixLog} {$response->getStatusCode()} - {$response->getBody()}\n\n");

         if($response->getStatusCode() == 204){
            return response()->json([
                'message' => "Boleto cancelado. Confira no app do inter se o mesmo foi cancelado",
                'status' => 204
                ],204
            );
        }

        return response()->json([
            'message' => "Ocorreu um problema ao cancelar o boleto, confira mais detalhes no log",
            'status' => 500
            ],500
        );
    }

    public function getSaldo(){
        $headers =  [
            'Authorization' => 'Bearer ' . $this->checkOAuthLogin(),
            "Content-Type" => "application/json"
        ];

        $response = $this->lnService->get(
            $this->url . "banking/v2/saldo", 
            $headers,
            $this->certKey()
        );

        if($response->getStatusCode() == 200){
            return $response->getBody();
        }

        return 0;
    }

    public function logs($message){
        Log::build([
            'driver' => 'daily',
            'path' => storage_path('logs/gerar-boleto/boleto.log'),
        ])->info($message);
    }

}
