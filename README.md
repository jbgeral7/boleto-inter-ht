
# Gerador de boleto Inter

Sistema para geração e envio de boleto automaticamente através do banco inter para conta PJ (Não funciona para MEI)

- Cadastro de clientes
- Cadastro de Serviços
- Atrelar serviços a clientes
- Geração automática de boleto
- Geração de boleto avulso
- Envio automático de boleto via E-mail
- Envio automático de boleto via WhatsApp
- Envio de boleto avulso via E-mail
- Envio de boleto avulso via WhatsApp
- Consulta de saldo na conta do Inter
## Requerimentos

O projeto não irá funcionar sem um banco de dados em memória, recomendo o Redis

- PHP Version >= 7.3
- BCMath PHP Extension
- Ctype PHP Extension
- Fileinfo PHP extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- Redis ou Memcached (Recomendo o Redis)

## Instalação

Clone o projeto

```
  git clone https://github.com/leonardop21/boleto-inter
```

Execute o composer

```
composer install
```

Renomeie o .env.example para .env e preencha as variáveis. Exemplo de preenchimento das variáveis do projeto

```
DARK_MODE= HABILITA O TEMA ESCURO - TRUE or False"
PAGINATION_LIMIT= Limite de paginação no sistema ex: 10
TIME_CACHE_IN_SECONDS= Cache do sistema em segundos, ex: 604800

EMAIL_DUVIDA= E-mail que o cliente poderá responder
NOTIFY_SEND_BOLETO= Este e-mail receberá uma notificação após o envio ou falha nos envios
NAME_SIGNATURE_MAIL= Nome do email
SITE_URL_REDIRECT_EMAIL= Site que aparecerá no corpo do e-mail

#Inter
INTER_PATH_CRT=caminho_arquivo.crt
INTER_PATH_KEY=caminho_arquvivo.key
INTER_BASE_URL="https://cdpj.partners.bancointer.com.br/"
INTER_CLIENT_ID="xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
INTER_CLIENT_SECRET="xxxxxxxxxxxxxxxxxxxxx"
INTER_CLIENT_SCOPE="extrato.read boleto-cobranca.read boleto-cobranca.write"


#PIX
CHAVE_PIX=

# Necessário preencher para envio de mensagens via WhastApp
WHATSAPP_BASE_URL= Url do projeto no WhatsAppp
WHATSAPP_SECRET_KEY= Chave Secreta
WHATSAPP_SESSION="Nome da sessão gerada no servidor do WhastApp"
NOTIFY_SEND_BOLETO_WHATSAPP="Número que irá receber uma mensgem, quando o envio pelo whats for finalziado"
```

Rode as migrations

```
php artisan migrate
```

Crie seu usuário através do Tinker

Faça login no sistema
- Cadastre um serviço
- Cadastre um cliente
- Atrele o serviço ao cliente

## Gerar boleto automaticamente

Para gerar o boleto automaticamente, rode o comando

```
php artisan ln:auto_generate_boleto
```

O sistema irá procurar clientes com o status "ativo" e com serviços atrelados, irá gerar o boleto, enviar por e-mail e se o WhatsApp estiver configurado, também será enviado pelo WhatsApp.

### Logs
Você pode conferir os logs da geração do boleto e do envio na pasta /storage/logs/gerar-boleto/boleto-ano-mes-dia.log


## Screenshots


### Página inicial
![Página inicial](https://i.imgur.com/RsC5KcU.png)



### Cadastro de serviços
![Cadastro de serviços](https://i.imgur.com/SaXMvOf.png)


### Cadastro de Cliente
![Cadastro de serviços](https://i.imgur.com/7clMNgp.png)

### Geração de boleto avulso

![Boleto Avulso](https://i.imgur.com/TUyDNKh.png)

### Login no WhatsApp
![Login no WhatsApp](https://i.imgur.com/XWadznx.png)

### Envio via E-mail
![Envio de boleto por e-mail](https://i.imgur.com/YGAAf3z.png)

### Envio via WhatsApp 
![Envio via WhatsApp](https://i.imgur.com/uAS1xCl.png)

### Envio Via WhatsApp Anexo
![Envio anexo](https://i.imgur.com/oZzvVtl.png)
## Créditos

- [Template E-mail - ckissi](https://github.com/ckissi/responsive-html-email-templates)
- [wppconnect-team](https://github.com/wppconnect-team)
- [ColorlibHQ](https://github.com/ColorlibHQ/AdminLTE)

## Este projeto é útil para você? Aceito um café ☕

Este projeto te auxiliou de alguma forma? Então, que tal me pagar um café? ☕☕
## License

[Mozilla Public License 2.0](https://choosealicense.com/licenses/mpl-2.0/)
