@extends('Backend.Layouts.panel')
@section('title','Serviços')
@section('content')

<div class="row">
    <div class="col-12">
        <a href="{{route('backend.service.create')}}" class="btn btn-info mb-3 float-right">Adicionar</a>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lista de Serviços</h3>
            </div>
            <div class="card-body p-0">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 10px">Id</th>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $record)
                            <tr class="{{$record->status  == '1' ? 'table' : 'bg-danger'}}">
                                <td>{{$record->id}}</td>
                                <td>{{$record->name}}</td>
                                <td>{{$record->description}}</td>
                                <td>{{'R$ '. number_format($record->price, 2, ',', '.') }}</td>
                                <td>{{$record->status == 1 ? 'Ativo' : 'Desativado'}}</td>
                                <td><a class="btn btn-info" href="{{route('backend.service.edit', ['id' => $record->id])}}">Editar</a></td>
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
