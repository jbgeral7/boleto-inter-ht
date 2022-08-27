<?php

namespace App\Interfaces;

interface BoletoInterface
{
    public function paginate();
    public function store($data);
    public function cancel($id);
}
