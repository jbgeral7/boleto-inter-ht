<?php

namespace App\Models;

use App\Models\Boleto;
use App\Models\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customers';
    protected $guarded = [];

    public function services(){
        return $this->belongsToMany(Service::class);
    }

    public function boletos(){
        return $this->hasMany(Boleto::class);
    }
}
