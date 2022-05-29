@extends('Backend.Layouts.panel')
@section('title','Clientes')
@section('content')

<div class="row">
    <div class="col-12">
        <a href="{{route('backend.customer.create')}}" class="btn btn-info mb-3 float-right">Adicionar</a>
    </div>
</div>

<div class="row">
    <div class="col-md-8 offset-md-2">
      <form method="get" action="#">
        <div class="input-group">
          <input type="search" class="form-control form-control-lg" placeholder="Pesquisar cliente" data-com.bitwarden.browser.user-edited="yes">
          <div class="input-group-append">
            <button type="submit" class="btn btn-lg btn-default">
            <i class="fa fa-search"></i>
            </button>
          </div>
        </div>
      </form>
    </div>
</div>

<div class="row">
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lista de clientes</h3>
            </div>
            <div class="card-body p-0">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 10px">Id</th>
                            <th>Nome</th>
                            <th>Nome Fantasia</th>
                            <th>CPF/CNPJ</th>
                            <th>Status</th>
                            <td>Editar</td>
                            <td>Boleto</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $record)
                            <tr class="{{$record->status  == '1' ? 'table' : 'bg-danger'}}">
                                <td>{{$record->id}}</td>
                                <td>{{$record->name}}</td>
                                <td>{{$record->fantasy_name}}</td>
                                <td>{{$record->cpf_cnpj}}</td>
                                <td>{{$record->status == 1 ? 'Ativo' : 'Desativado'}}</td>
                                <td><a class="btn btn-warning" href="{{route('backend.customer.edit', ['id' => $record->id])}}">Editar</a></td>
                                <td width="140"><a href="{{route('backend.boleto.index', ['cliente' => 'cliente', 'customer_id' => $record->id])}}" class="btn btn-success">Ver boletos</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-center">
            {!! $records->links('pagination::bootstrap-4') !!}
        </div>
    </div>
</div>
@endsection
