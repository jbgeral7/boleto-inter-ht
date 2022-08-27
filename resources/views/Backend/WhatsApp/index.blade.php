@extends('Backend.Layouts.panel')
@section('title','Login no WhatsApp')
@section('content')

<div class="row">
    <div class="col-12">
        <a href="{{route('backend.customer.create')}}" class="btn btn-info mb-3 float-right">Adicionar</a>
    </div>
</div>

<div class="row">
    <login-whats qrcode-or-status="{{json_encode($response)}}"></login-whats>
</div>

@endsection
