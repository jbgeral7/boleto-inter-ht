@extends('Backend.Layouts.panel')
@section('title','Gerar boleto avulso')
@section('content')

<div class="row">
    <div class="col-12">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Gerar boleto avulso</h3>
          </div>
          <form method="post" action="{{route('backend.boleto.store')}}">
           @csrf
            <div class="card-body">
              <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name">Cliente*</label>
                        <select name="customer_id" id="customer_id" class="form-control" required>
                            <option value="">Selecione o Cliente</option>
                            @foreach($customers as $customer)
                                <option value="{{$customer->id}}">{{$customer->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="description">Descrição</label>
                        <input type="text" class="form-control" name="description" id="description" value="{{old('description')}}" placeholder="Boleto referente a entrada do site abc.xyz" maxlength="255">
                    </div>
              </div>
              <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="my_number">Seu número <small>(Para o seu controle interno)</small></label>
                        <input type="text" class="form-control" name="my_number" id="my_number" value="{{old('my_number')}}" placeholder="Ex: ABC-123" maxlength="255">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="price">VALOR* <small>(Não use R$)</small></label>
                        <input type="text" class="form-control" name="price" id="price" value="{{old('price')}}" placeholder="199,75" required>
                    </div>
              </div>
              <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="due_date">Data de vencimento</small></label>
                        <input type="date" class="form-control" name="due_date" id="due_date" value="{{old('due_date')}}" placeholder="Data de vencimento" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="date_to_pay_after_due_date">Quantos dias após o vencimento, o boleto ainda poderá ser pago?*</label>
                        <input type="number" min="0" class="form-control" name="date_to_pay_after_due_date" id="date_to_pay_after_due_date" value="{{old('date_to_pay_after_due_date')}}" placeholder="3" required>
                        <small>Permite o pagamento do boleto mesmo após o vencimento. Preencha com 0 para não permitir pagamento após a data de vencimento</small>
                    </div>
              </div>
              <button type="submit" class="btn btn-primary">Gerar Boleto</button>
            </div>
          </form>
        </div>
    </div>
</div>
@endsection