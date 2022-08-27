<?php

namespace App\Observers;

use App\Interfaces\WhatsAppInterface;

class WhatsAppObserver
{
    public function __construct(WhatsAppInterface $whatsapp){
        $this->whatsapp = $whatsapp;
    }

    public function created(){
        $this->whatsapp->forgetCacheTag();
    }
    public function updated(){
        $this->whatsapp->forgetCacheTag();
    }
    public function deleted(){
        $this->whatsapp->forgetCacheTag();
    }
}
