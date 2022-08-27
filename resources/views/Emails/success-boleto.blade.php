<!DOCTYPE html>
<html>
<head>
    <title>Boletos enviados com sucesso</title>
</head>
<body>
    <h1>{{ $title }}</h1>
    <p>Boletos enviado com sucesso para os clientes abaixo:</p>
        <ul>
            @foreach($customers as $customer)
                <li>Nome: {{$customer->customer->name}}</li>
                <li>id: {{$customer->customer->id}} 
                <li>Nome fantasia: {{$customer->customer->fantasy_name}}</li>
                <li>-------------------------------------------------------</li>
                <br><br>
            @endforeach
        </ul>
     
    <p>Enviado automaticamente!</p>
</body>
</html>
