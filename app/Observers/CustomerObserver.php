<?php

namespace App\Observers;

use App\Interfaces\CustomerInterface;

class CustomerObserver
{
    public function __construct(CustomerInterface $customer){
        $this->customer = $customer;
    }

    public function created(){
        $this->customer->forgetCacheTag();
    }

    public function updated(){
        $this->customer->forgetCacheTag();
    }
    
    public function deleted(){
        $this->customer->forgetCacheTag();
    }
}
