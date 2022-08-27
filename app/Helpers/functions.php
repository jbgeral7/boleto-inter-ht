<?php 

function convertBRL($number){
    return number_format($number, 2, ',', '.');
}

function checkCustomerValueService($customer){
    $total_price = 0;
    foreach($customer->services as $service){
        $total_price += number_format($service->price, 2, '.', '');
    }
    return $total_price;
}