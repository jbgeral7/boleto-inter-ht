<?php

namespace App\Observers;

use App\Interfaces\BoletoInterface;

class BoletoObServe
{
    public function __construct(BoletoInterface $boleto){
        $this->boleto = $boleto;
    }

    public function created(){
        $this->boleto->forgetCacheTag();
    }

    public function updated(){
        $this->boleto->forgetCacheTag();
    }
    
    public function deleted(){
        $this->boleto->forgetCacheTag();
    }
}
