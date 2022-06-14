<?php

namespace App\Repositories;

use App\Models\Boleto;
use App\Models\Customer;
use App\Services\InterService;
use App\Interfaces\BoletoInterface;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class BoletoRepository extends AbstractRepository implements BoletoInterface
{
    protected $model = Boleto::class;
    protected $abs = AbstractRepository::class;

    public function __construct(InterService $interService)
    {
        parent::__construct();
        $this->interService = $interService;

    }

    public function store($data)
    {   
        if(isset($data['price_cron'])){
            $data['price'] = $data['price_cron'];
            unset($data['price_cron']);
        }else {
            $data['price'] = floatval(str_replace(',', '.', str_replace('.', '', $data['price'])));
        }

        $data['customer_id'] = (int) $data['customer_id'];

        $customer = $this->findCustomer($data['customer_id']);

        $response = $this->generatePaymentTicket($customer, $data);

        if($response->getStatusCode() != 200){
            return $response;
        }

        $this->model = Boleto::class;
        $decode = json_decode($response);
        $data['my_number'] = $decode->seuNumero;
        $data['our_number'] = $decode->nossoNumero;
        $data['bar_code'] = $decode->codigoBarras;
        $data['digitable_line'] = $decode->linhaDigitavel;

        $response = $this->abs::store($data);
        return response($response, 200);
    }

    public function generatePaymentTicket($customer, $data){
        return $this->interService->generatePaymentTicket($customer, $data);
    }

    public function findCustomer($id){
        $this->model = Customer::class;
        return $this->abs::find($id);
    }

    public function cancel($id)
    {
        dd('');
        
    }

    public function withPaginate($with, $customer_id){
        $page = request()->get('page', 1);

        if($customer_id){
            if(!Cache::tags($this->tableCache)->has('_page_get_boleto_' . $customer_id . "_" . $page)){
                $records = $this->model::where('customer_id', $customer_id)->with('customer')->orderBy('id', 'desc')->paginate(20);
                Cache::tags([$this->tableCache])->put('_page_get_boleto_' .  $customer_id . "_" . $page, $records, $this->time_cache);
            }
            return Cache::tags([$this->tableCache])->get('_page_get_boleto_' .  $customer_id . "_" . $page);
        }

        if(!Cache::tags($this->tableCache)->has('_page_with_' . $page)){
            $records = $this->model::with($with)->orderBy('id', 'desc')->paginate(20);
            Cache::tags([$this->tableCache])->put('_page_with_' . $page, $records, $this->time_cache);
        }
        return Cache::tags([$this->tableCache])->get('_page_with_' . $page);
    }

    public function getPdf($find){
        $find = Boleto::where('id', $find)->with('customer')->firstOrFail();
        
        $exists = $this->findPath($find);

        if($exists) return $exists;
        
        $response = $this->interService->getPdf($find->our_number);

        if($response->getStatusCode() == 200){
            $data['path'] = $response->original['path'];
            $data['boleto'] = $response->original['boleto'];
            $response = $this->abs::update($data, $find->id);
            return response()->json(["url_download" => $this->findPath($response), "name_boleto" => $this->namePdf($find->customer)], 200);
        }

        return $response;
    }

    public function findPath($find){
        $boleto = base_path() . "/storage/app/" . $find->path . $find->boleto;

        if(isset($find->path) && isset($find->boleto) && file_exists($boleto)){
            return response()->json(["url_download" => $boleto, "name_boleto" => $this->namePdf($find->customer)], 200);
        }

        return false;
    }

    public function namePdf($customer){
        return $customer->fantasy_name ? $customer->fantasy_name : $customer->name;
    }

    public function sendBoleto($boleto_id){
        $find = Boleto::where('id', $boleto_id)->with('customer')->firstOrFail();
        
        $permission = $this->verifyPermissionSendBoleto($find, 'email_notify');

        if(!$permission){
            return response("O cliente está configurado para não receber boletos por e-mail, verifique o cadastro do cliente", 401);
        }

        $exists = $this->findPath($permission);

        if(!$exists){
            return response("Boleto não enviado, o arquivo não existe", 404);
        }

        $data["month"] = $this->translateMonth(date("F"));
        $data['due_date'] = explode("-", $find->due_date)[2];
        $data["email"] = $find->customer->email;
        $data["title"] = "Sua fatura de " . $data["month"] . " chegou";
        $data["customer"] = $find->customer;
        $data["boleto"] = $find;
        $data["services"] = $find->customer->services;
 
        $file = $exists->original['url_download'];
  
        Mail::send('Emails.boleto', $data, function($message)use($data, $file) {
            $message->to($data["email"], $data["email"])->bcc(env('NOTIFY_SEND_BOLETO'))
                ->subject($data["title"]);
 
            $message->attach($file);
        });

        return response("boleto enviado com sucesso", 200);
    }

    public function sendAvulseBoleto($id){
        $find = Boleto::where('id', $id)->with('customer')->firstOrFail();
        
        $permission = $this->verifyPermissionSendBoleto($find, 'email_notify');

        if(!$permission){
            return response("O cliente está configurado para não receber boletos por e-mail, verifique o cadastro do cliente", 401);
        }

        $exists = $this->findPath($permission);

        if(!$exists){
            return response("Boleto não enviado, o arquivo não existe", 404);
        }

        $date = explode("-", $find->due_date);
        $data['due_date'] = explode("-", $find->due_date)[2];
        $data["month"] = $this->convertNumberDateToString($date[1]);
        $data["email"] = $find->customer->email;
        $data["title"] = "Seu boleto de {$data['month']} está disponível";
        $data["customer"] = $find->customer;
        $data["boleto"] = $find;

        $file = $exists->original['url_download'];
  
        Mail::send('Emails.boleto-avulso', $data, function($message)use($data, $file) {
            $message->to($data["email"], $data["email"])->bcc(env('NOTIFY_SEND_BOLETO'))
                ->subject($data["title"]);
 
            $message->attach($file);
        });

        return response("boleto enviado com sucesso", 200);
    }

    public function sendWhatsApp($boleto_id){
        $find = Boleto::where('id', $boleto_id)->with('customer')->firstOrFail();
        
        $permission = $this->verifyPermissionSendBoleto($find, 'whatsapp_notify');

        if(!$permission){
            return response("O cliente está configurado para não receber boletos pelo WhatsApp, verifique o cadastro do cliente", 401);
        }

        $exists = $this->findPath($permission);

        if(!$exists){
            return response("Boleto não enviado, o arquivo não existe", 404);
        }


    }

    public function verifyPermissionSendBoleto($find, $type){
       return $find->customer->$type == 1 ? $find : false;
    }

    public function failedSendBoleto($failed_id){
        $data['customers'] = Boleto::whereIn('id', $failed_id)->with('customer')->get();
        $data['email'] = env('NOTIFY_SEND_BOLETO');
        $data['title'] = "Falha ao enviar boletos";

        Mail::send('Emails.failed-boleto', $data, function($message)use($data) {
            $message->to($data["email"], $data["email"])
                ->subject($data["title"]);
         });

         return response("ok", 200);
    }
    
    public function successSendBoleto($success_id){
        $data['customers'] = Boleto::whereIn('id', $success_id)->with('customer')->get();
        $data['failed_id'] = $success_id;
        $data['email'] = env('NOTIFY_SEND_BOLETO');
        $data['title'] = "Boletos enviados com sucesso";

        Mail::send('Emails.success-boleto', $data, function($message)use($data) {
            $message->to($data["email"], $data["email"])
                ->subject($data["title"]);
         });

         return response("ok", 200);
    }

    public function convertNumberDateToString($month){
        switch(strtolower($month)){
            case "01": $month = "Janeiro"; break;
            case "02": $month = "Fevereiro"; break;
            case "03": $month = "Março"; break;
            case "04": $month = "Abril"; break;
            case "05": $month = "Maio"; break;
            case "06": $month = "Junho"; break;
            case "07": $month = "Julho"; break;
            case "08": $month = "Agosto"; break;
            case "09": $month = "Setembro"; break;
            case "10": $month = "Outubro"; break;
            case "11": $month = "Novembro"; break;
            case "12": $month = "Dezembro"; break;
            default: $month = "Unknown"; break;
        }

        return $month;
    }
    
    public function translateMonth($month){
        switch(strtolower($month)){
            case "january": $month = "Janeiro"; break;
            case "february": $month = "Fevereiro"; break;
            case "march": $month = "Março"; break;
            case "april": $month = "Abril"; break;
            case "may": $month = "Maio"; break;
            case "june": $month = "Junho"; break;
            case "july": $month = "Julho"; break;
            case "august": $month = "Agosto"; break;
            case "september": $month = "Setembro"; break;
            case "october": $month = "Outubro"; break;
            case "november": $month = "Novembro"; break;
            case "december": $month = "Dezembro"; break;
            default: $month = "Unknown"; break;
        }

        return $month;
    }
}