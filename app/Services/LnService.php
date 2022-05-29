<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use phpDocumentor\Reflection\Types\Boolean;
use PhpParser\Node\Expr\Cast\Array_;
use PhpParser\Node\Expr\Cast\Bool_;

class LnService
{
    public function get(String $url = '', Array $headers = [], Array $options = []){
        return Http::withHeaders($headers)->withOptions($options)->get($url);
    }

    public function post(String $url = '', Array $headers = [], Array $data = [], Array $options = [], Bool $enableForm = false){
        if($enableForm){
            return  Http::asForm()->withHeaders($headers)->withOptions($options)->post($url, $data);
        }

        return Http::withHeaders($headers)->withOptions($options)
        ->post($url,$data);
    }

    public function delete(String $url = '', Array $headers = [], Array $options = []){
        return Http::withHeaders($headers)->withOptions($options)->delete($url);
    }
}
