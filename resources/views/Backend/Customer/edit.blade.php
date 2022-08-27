@extends('Backend.Layouts.panel')
@section('title','Editar cliente -' . $customer->name)
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
            <h3 class="card-title">Editar {{$customer->name}}</h3>
          </div>
          <form method="post" action="{{route('backend.customer.update', ['id' => $customer->id])}}">
           @method('put')
           @csrf
            <div class="card-body">
              <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name">Nome*</label>
                        <input type="text" class="form-control" name="name" id="name" value="{{old('name', $customer->name)}}" placeholder="Nome completo" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="email">E-mail*</label>
                        <input type="email" class="form-control" name="email" id="email" value="{{old('email', $customer->email)}}" placeholder="E-mail" required>
                    </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="corporate_name">Razão Social</label>
                    <input type="text" class="form-control" name="corporate_name" id="corporate_name" value="{{old('corporate_name', $customer->corporate_name)}}" placeholder="Razão Social">
                </div>
                <div class="form-group col-md-6">
                    <label for="fantasy_name">Nome Fantasia</label>
                    <input type="text" class="form-control" name="fantasy_name" id="fantasy_name" value="{{old('fantasy_name', $customer->fantasy_name)}}" placeholder="Nome fantasia">
                </div>
              </div>
              <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="cpf_cnpj">CPF/CNPJ*</label>
                        <input type="text" class="form-control" name="cpf_cnpj" id="cpf_cnpj" value="{{old('cpf_cnpj', $customer->cpf_cnpj)}}" placeholder="Rua amazonas" minlength="11" maxlength="14" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="address">Endereço*</label>
                        <input type="text" class="form-control" name="address" id="address" value="{{old('address', $customer->address)}}" placeholder="Rua amazonas" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="address_number">Número*</label>
                        <input type="text" class="form-control" name="address_number" id="address_number" value="{{old('address_number', $customer->address_number)}}" placeholder="2758 B" required>
                    </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="state">Estado*</label>
                    <select name="state" id="state" class="form-control" required>
                        @foreach($states as $state)
                            <option value="{{$state}}" {{($state == $customer->state)? 'selected':'' }}>{{$state}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="city">Cidade*</label>
                    <input type="text" class="form-control" name="city" id="city" value="{{old('city', $customer->city)}}" placeholder="Cidade" required>
                </div>
                <div class="form-group col-md-2">
                    <label for="zipcode">Cep*</label>
                    <input type="text" class="form-control" name="zipcode" id="zipcode" value="{{old('zipcode', $customer->zipcode)}}" placeholder="Cep" required>
                </div>
              </div>
              <div class="form-row">
                  <div class="-form-group col-md-6">
                    <label for="district">Bairro*</label>
                    <input type="text" class="form-control" name="district" id="district" value="{{old('district', $customer->district)}}" placeholder="Bairro" required>
                  </div>
                  <div class="-form-group col-md-6">
                    <label for="complement">Complemento</label>
                    <input type="text" class="form-control" name="complement" id="complement" value="{{old('complement', $customer->complement)}}" placeholder="Casa">
                  </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="phone">Telefone</label>
                    <input type="text" class="form-control" name="phone" id="phone" value="{{old('phone', $customer->phone)}}" placeholder="45 3242-0000">
                </div>
                <div class="form-group col-md-4">
                    <label for="whatsapp">WhatsApp</label>
                    <input type="text" class="form-control" name="whatsapp" id="whatsapp" value="{{old('whatsapp', $customer->whatsapp)}}" placeholder="45 9 9999-9999">
                </div>
                <div class="form-group col-md-4">
                    <label for="telegram">Telegram</label>
                    <input type="text" class="form-control" name="telegram" id="telegram" value="{{old('telegram', $customer->telegram)}}" placeholder="nickname">
                </div>
              </div>
              <div class="form-row">
                  <div class="form-group col-md-4">
                    <label for="email_notify">Notificação por e-mail*</label>
                    <select name="email_notify" id="email_notify" class="form-control" required>
                        <option value="1" {{$customer->email_notify == 1 ? 'selected' : ''}}>SIM</option>
                        <option value="0" {{$customer->email_notify == 0 ? 'selected' : ''}}>NÃO</option>
                      </select>
                      <small>Envia o boleto por e-mail ao cliente</small>
                  </div>
                  <div class="form-group col-md-4">
                    <label for="whatsapp_notify">Notificação por WhatsApp*</label>
                    <select name="whatsapp_notify" id="whatsapp_notify" class="form-control" required>
                        <option value="">Selecione...</option>
                        <option value="1" {{$customer->whatsapp_notify == 1 ? 'selected' : ''}}>SIM</option>
                        <option value="0" {{$customer->whatsapp_notify == 0 ? 'selected' : ''}}>NÃO</option>
                      </select>
                      <small>Envia o boleto por WhatsApp ao cliente (Disponível com assinatura adicional)</small>
                  </div>
                  <div class="form-group col-md-4">
                    <label for="telegram_notify">Notificação por Telegram</label>
                    <select name="telegram_notify" id="telegram_notify" class="form-control">
                        <option value="0" {{$customer->telegram_notify == 0 ? 'selected' : ''}}>NÃO</option>
                        <option value="1" {{$customer->telegram_notify == 0 ? 'selected' : ''}}>SIM</option>
                      </select>
                      <small>Envia o boleto via telegram ao cliente (Ainda não implementado)</small>
                  </div>
              </div>
              <div class="form-row">
                  <div class="form-group col">
                        <label>Serviços*</label>
                        <div class="select2-blue">
                            <select name="services[]" class="select2" multiple="multiple" data-placeholder="Selecione um serviço" data-dropdown-css-class="select2-blue" style="width: 100%;" required>
                                @foreach($services as $service)
                                 <option value="{{$service->id}}" {{in_array($service->id, $selectedService) ? 'selected' : ''}}>{{$service->name}}</option>
                                @endforeach
                            </select>
                        </div>
                  </div>
              </div>
              <div class="form-row">
                <div class="form-group col">
                    <label for="expiration_day_boleto">Melhor dia para pagamento*</label>
                    <select name="expiration_day_boleto" id="expiration_day_boleto" class="form-control" required>
                        <option value="">Selecione um dia</option>
                        @for($i=1; $i <= 25; $i++)
                            <option value="{{$i}}" {{$customer->expiration_day_boleto == $i ? 'selected' : ''}}>{{$i}}</option>
                        @endfor
                    </select>
                    <small>Ex: Todo dia 10 será o vencimento do boleto</small>
                  </div>
                <div class="form-group col">
                    <label for="status">Status do cliente no sistema</label>
                    <select name="status" id="status" class="form-control">
                        <option value="1" {{$customer->status == 1 ? 'selected' : ''}}>ATIVO</option>
                        <option value="0" {{$customer->status == 0 ? 'selected' : ''}}>INATIVO</option>
                      </select>
                      <small>Status Inativo não gera boleto</small>
                  </div>
              </div>
              <button type="submit" class="btn btn-primary">Atualizar cliente</button>
            </div>
          </form>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .select2-container--default .select2-selection--multiple {
        background-color: #343a40 !important;
        border: 1px solid #6c757d;
    }
    .dark-mode .select2-blue .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #343a40;
        border: none;
    }
    .dark-mode .select2-container .select2-search--inline .select2-search__field {
        display: none;
    }
</style>
@endsection