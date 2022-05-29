<?php

namespace App\Observers;

use App\Interfaces\ServiceInterface;

class ServiceObserver
{
    public function __construct(ServiceInterface $service){
        $this->service = $service;
    }

    public function created(){
        $this->service->forgetCacheTag();
    }
    public function updated(){
        $this->service->forgetCacheTag();
    }
    public function deleted(){
        $this->service->forgetCacheTag();
    }
}
