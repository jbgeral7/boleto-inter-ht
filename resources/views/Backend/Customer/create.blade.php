@extends('Backend.Layouts.panel')
@section('title','Adicionar cliente')
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
            <h3 class="card-title">Adicionar Cliente</h3>
          </div>
          <form method="post" action="{{route('backend.customer.store')}}">
           @csrf
            <div class="card-body">
              <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name">Nome*</label>
                        <input type="text" class="form-control" name="name" id="name" value="{{old('name')}}" placeholder="Nome completo" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="email">E-mail*</label>
                        <input type="email" class="form-control" name="email" id="email" value="{{old('email')}}" placeholder="E-mail" required>
                    </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="corporate_name">Razão Social</label>
                    <input type="text" class="form-control" name="corporate_name" id="corporate_name" value="{{old('corporate_name')}}" placeholder="Razão Social">
                </div>
                <div class="form-group col-md-6">
                    <label for="fantasy_name">Nome Fantasia</label>
                    <input type="text" class="form-control" name="fantasy_name" id="fantasy_name" value="{{old('fantasy_name')}}" placeholder="Nome fantasia">
                </div>
              </div>
              <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="cpf_cnpj">CPF/CNPJ*</label>
                        <input type="text" class="form-control" name="cpf_cnpj" id="cpf_cnpj" value="{{old('cpf_cnpj')}}" placeholder="Rua amazonas" minlength="11" maxlength="14" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="address">Endereço*</label>
                        <input type="text" class="form-control" name="address" id="address" value="{{old('address')}}" placeholder="Rua amazonas" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="address_number">Número*</label>
                        <input type="text" class="form-control" name="address_number" id="address_number" value="{{old('address_number')}}" placeholder="2758 B" required>
                    </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="state">Estado*</label>
                    <select name="state" id="state" class="form-control" required>
                        <option value="">SELECIONAR</option>
                        <option value="AC">Acre</option>
                        <option value="AL">Alagoas</option>
                        <option value="AP">Amapá</option>
                        <option value="AM">Amazonas</option>
                        <option value="BA">Bahia</option>
                        <option value="CE">Ceará</option>
                        <option value="DF">Distrito Federal</option>
                        <option value="ES">Espírito Santo</option>
                        <option value="GO">Goiás</option>
                        <option value="MA">Maranhão</option>
                        <option value="MT">Mato Grosso</option>
                        <option value="MS">Mato Grosso do Sul</option>
                        <option value="MG">Minas Gerais</option>
                        <option value="PA">Pará</option>
                        <option value="PB">Paraíba</option>
                        <option value="PR">Paraná</option>
                        <option value="PE">Pernambuco</option>
                        <option value="PI">Piauí</option>
                        <option value="RJ">Rio de Janeiro</option>
                        <option value="RN">Rio Grande do Norte</option>
                        <option value="RS">Rio Grande do Sul</option>
                        <option value="RO">Rondônia</option>
                        <option value="RR">Rorâima</option>
                        <option value="SC">Santa Catarina</option>
                        <option value="SP">São Paulo</option>
                        <option value="SE">Sergipe</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="city">Cidade*</label>
                    <input type="text" class="form-control" name="city" id="city" value="{{old('city')}}" placeholder="Cidade" required>
                </div>
                <div class="form-group col-md-2">
                    <label for="zipcode">Cep*</label>
                    <input type="text" class="form-control" name="zipcode" id="zipcode" value="{{old('zipcode')}}" placeholder="Cep" required>
                </div>
              </div>
              <div class="form-row">
                  <div class="-form-group col-md-6">
                    <label for="district">Bairro*</label>
                    <input type="text" class="form-control" name="district" id="district" value="{{old('district')}}" placeholder="Bairro" required>
                  </div>
                  <div class="-form-group col-md-6">
                    <label for="complement">Complemento</label>
                    <input type="text" class="form-control" name="complement" id="complement" value="{{old('complement')}}" placeholder="Casa">
                  </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="phone">Telefone</label>
                    <input type="text" class="form-control" name="phone" id="phone" value="{{old('phone')}}" placeholder="45 3242-0000">
                </div>
                <div class="form-group col-md-4">
                    <label for="whatsapp">WhatsApp</label>
                    <input type="text" class="form-control" name="whatsapp" id="whatsapp" value="{{old('whatsapp')}}" placeholder="45 9 9999-9999">
                </div>
                <div class="form-group col-md-4">
                    <label for="telegram">Telegram</label>
                    <input type="text" class="form-control" name="telegram" id="telegram" value="{{old('telegram')}}" placeholder="nickname">
                </div>
              </div>
              <div class="form-row">
                  <div class="form-group col-md-4">
                    <label for="email_notify">Notificação por e-mail*</label>
                    <select name="email_notify" id="email_notify" class="form-control" required>
                        <option value="1">SIM</option>
                        <option value="0">NÃO</option>
                      </select>
                      <small>Envia o boleto por e-mail ao cliente</small>
                  </div>
                  <div class="form-group col-md-4">
                    <label for="whatsapp_notify">Notificação por WhatsApp*</label>
                    <select name="whatsapp_notify" id="whatsapp_notify" class="form-control" required>
                        <option value="">Selecione...</option>
                        <option value="1">SIM</option>
                        <option value="0">NÃO</option>
                      </select>
                      <small>Envia o boleto por WhatsApp ao cliente (Disponível com assinatura adicional)</small>
                  </div>
                  <div class="form-group col-md-4">
                    <label for="telegram_notify">Notificação por Telegram</label>
                    <select name="telegram_notify" id="telegram_notify" class="form-control">
                        <option value="0">NÃO</option>
                        <option value="1">SIM</option>
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
                                    <option value="{{$service->id}}">{{$service->name}}</option>
                                @endforeach
                            </select>
                        </div>
                  </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="expiration_day_boleto">Melhor dia para pagamento*</label>
                    <select name="expiration_day_boleto" id="expiration_day_boleto" class="form-control" required>
                        <option value="">Selecione um dia</option>
                        @for($i=1; $i <= 25; $i++)
                            <option value="{{$i}}">{{$i}}</option>
                        @endfor
                    </select>
                    <small>Ex: Todo dia 10 será o vencimento do boleto</small>
                  </div>
                <div class="form-group col-md-6">
                    <label for="status">Status do cliente no sistema</label>
                    <select name="status" id="status" class="form-control">
                        <option value="1">ATIVO</option>
                        <option value="0">INATIVO</option>
                      </select>
                      <small>Status Inativo não gera boleto</small>
                  </div>
              </div>
              <button type="submit" class="btn btn-primary">Adicionar cliente</button>
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