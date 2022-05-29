<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'corporate_name' => 'max:255',
            'razao_social' => 'max:255',
            'fantasy_name' => 'max:255',
            'cpf_cnpj' => 'required|max:50',
            'zipcode' => 'required|max:20',
            'address' => 'required|max:255',
            'address_number' => 'required|max:10',
            'city' => 'required|max:30',
            'state' => 'required|max:2',
            'district' => 'required|max:255',
            'complement' => 'max:255',
            'email' => 'required|max:255',
            'phone' => 'max:20',
            'whatsapp' => 'max:20',
            'telegram' => 'max:50',
            'email_notify' => 'required',
            'whatsapp_notify' => 'required',
            'telegram_notify' => 'required',
            'status' => 'required',
        ];   
    }
}
