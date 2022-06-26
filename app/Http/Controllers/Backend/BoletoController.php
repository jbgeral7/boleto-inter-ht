<?php

namespace App\Http\Controllers\Backend;

use App\Models\Boleto;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Interfaces\BoletoInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\BoletoRequest;
use App\Interfaces\CustomerInterface;
use Illuminate\Support\Facades\Storage;

class BoletoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(BoletoInterface $boleto, CustomerInterface $customer)
    {
        $this->boleto = $boleto;
        $this->customer = $customer;
    }
    public function index(Request $request)
    {
        $customer_id = $request->customer_id ? $request->customer_id : null;
        $records = $this->boleto->withPaginate(['customer'], $customer_id);
        return view('Backend.Boleto.index', compact('records', 'customer_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = $this->customer->get(true);
        return view('Backend.Boleto.create', compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BoletoRequest $request)
    {

       $response = $this->boleto->store($request->all());

       if($response->getStatusCode() != 200){
           $decode = json_decode($response);
           $mesesage =  "Falha ao gerar boleto {$decode->_embedded->errors[0]->message} código da resposta:  {$response->getStatusCode()} mensagem: {$decode->message}";
            
        return redirect()->back()
        ->withErrors(['message' => $mesesage]);
       }

        return redirect()->route('backend.boleto.index')
            ->with(['message' => 'Boleto gerado com sucesso!', 'alert-type' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sendEmail($id)
    {
        \Artisan::call("ln:send-boleto", ["boletos_id" => [0 => $id]]);

        $this->boleto->update(["email_notify_send" => date("Y-m-d H:i:s")], $id);

        return redirect()->route('backend.boleto.index')
            ->with(['message' => 'Boleto enviado com sucesso!', 'alert-type' => 'success']);
        
    }

    public function sendAvulseEmail($id){
        $this->boleto->sendAvulseBoleto($id);

        $this->boleto->update(["email_notify_send" => date("Y-m-d H:i:s")], $id);

        return redirect()->route('backend.boleto.index')
            ->with(['message' => 'Boleto enviado com sucesso!', 'alert-type' => 'success']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel($id)
    {
      $response = $this->boleto->cancel($id);
        
      $mesesage = $response->status == 204 ?  $response->message : $response->message;
      $type = $response->status == 204 ?  "success" : "error";

      return redirect()->back()
      ->with(['message' => $mesesage, 'alert-type' => $type]);
    
    }

    public function downloadPdf($boleto){
        $response = $this->boleto->getPdf($boleto);

        if($response->getStatusCode() != 200){
            $decode = json_decode($response);
            $mesesage =  "Falha ao baixar boleto: {$decode->detail} código da resposta:  {$response->getStatusCode()}";
             
         return redirect()->back()
         ->with(['message' => $mesesage, 'alert-type' => 'error']);
        }
        
        $response = $response->original;

        $nameBoleto = preg_replace('/[^a-z0-9]/i', '_', $response['name_boleto']);

        return response()->download($response['url_download'], $nameBoleto . ".pdf");
     }
}
