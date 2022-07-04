<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Interfaces\WhatsAppInterface;

class WhatsAppController extends Controller
{

    public function __construct(WhatsAppInterface $whatsapp){
        $this->whatsapp = $whatsapp;
    }


    public function index()
    {
        $response = $this->whatsapp->login();
        return view('Backend.Whatsapp.index', compact('response'));
        
    }

    public function qrcodeUpdate(){
       $response = $this->whatsapp->qrcodeUpdate();
        return json_decode($response->getBody()->getContents(),true);
    }
    public function closeSession(){
        return $this->whatsapp->closeSession();
    }

    public function getStatusConnection(){
        return $this->whatsapp->getStatusConnection();
    }

    public function sendMessageBeforeBoleto($boleto_id, $key){
        return $this->whatsapp->sendMessageBeforeBoleto($boleto_id);
    }
    
    public function notifySendBoletoWhatsApp($ids, $success = null) {
        return $this->whatsapp->notifySendBoletoWhatsApp($ids, $success);
    }
}
