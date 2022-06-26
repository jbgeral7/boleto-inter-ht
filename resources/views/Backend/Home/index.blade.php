@extends('Backend.Layouts.panel')
@section('title','Painel')
@section('content')


<section id="infos">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                   <div class="inner">
                      <h3 id="saldo-conta">R$ {{convertBRL($saldo)}}</h3>
                      <p>Saldo disponível</p>
                   </div>
                   <div class="icon">
                    <i class="fas fa-money-bill-wave"></i>
                   </div>
                   <a href="#" onclick="renderSaldo()" class="small-box-footer">Consultar Saldo <i class="fas fa-sync-alt"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
               <div class="small-box bg-info">
                  <div class="inner">
                     <h3>{{$qtdBoleto}}</h3>
                     <p>Boletos</p>
                  </div>
                  <div class="icon">
                    <i class="fas fa-money-check"></i>
                  </div>
                  <a href="{{route('backend.boleto.index')}}" class="small-box-footer">Saiba Mais <i class="fas fa-arrow-circle-right"></i></a>
               </div>
            </div>
            <div class="col-lg-3 col-6">
               <div class="small-box bg-danger">
                  <div class="inner">
                     <h3>{{$qtdService}}</h3>
                     <p>Serviços</p>
                  </div>
                  <div class="icon">
                    <i class="fas fa-terminal"></i>
                  </div>
                  <a href="{{route('backend.service.index')}}" class="small-box-footer">Saiba Mais <i class="fas fa-arrow-circle-right"></i></a>
               </div>
            </div>
            <div class="col-lg-3 col-6">
               <div class="small-box bg-warning">
                  <div class="inner">
                     <h3>{{$qtdCustomer}}</h3>
                     <p>Clientes</p>
                  </div>
                  <div class="icon">
                    <i class="fa fa-users"></i>
                  </div>
                  <a href="{{route('backend.customer.index')}}" class="small-box-footer">Saiba Mais <i class="fas fa-arrow-circle-right"></i></a>
               </div>
            </div>
         </div>
    </div>
</section>

<script>
    const url = "{{route('backend.get-saldo')}}";
    
    async function getSaldo() {
        try {
            let res = await fetch(url);
            return await res.json();
        } catch (error) {
            console.log(error);
        }
    }

    async function renderSaldo() {
        let saldoConta = document.getElementById("saldo-conta");
        saldoConta.innerText = "Atualizando..";
        let saldoDisponivel = await getSaldo();
        let saldo = saldoDisponivel.disponivel.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
        saldoConta.innerText = saldo;
    }
</script>

@endsection
