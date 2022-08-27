<?php

namespace App\Models;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Boleto extends Model
{
    use HasFactory;

    protected $table = 'boletos';
    protected $guarded = [];

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id')->with("services");
    }
   
}
