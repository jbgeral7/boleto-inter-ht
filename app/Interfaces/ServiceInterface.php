<?php

namespace App\Interfaces;

interface ServiceInterface
{
    public function paginate();
    public function store($data);
    public function edit($id);
    public function destroy($id);
}
