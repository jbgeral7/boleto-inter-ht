<template>
    <div>
      <div class="card">
        <div class="card-block">
          <div class="row">
            <div class="col-12 col-lg-6 mt-4 mb-4">
              <div v-if="status != 'CLOSED' && status != 'CONNECTED'">
                <ol class="font1-3em linheH-2-3">
                  <li>Abra o WhatsApp Business</li>
                  <li>Clique em <span class="bx bx-dots-vertical-rounded"></span>do lado superior direito e depois em aparelhos conectados</li>
                  <li>Clique em "Conectar um aparelho" e aponte o seu celular para o QRCODE ao lado</li>
                </ol>
              </div>
            <div class="m-3" v-if="status == 'CLOSED'">
              <h5 class="font1-3em linheH-2-3">
               A sessão foi encerrada automaticamente, aguarde, a página será recarregada em 3 segundos. <br />
               <small>Caso a página não seja recarregada <a href="/painel/whatsapp">clique aqui</a></small>
              </h5>
            </div>
            <div class="m-3" v-if="status == 'CONNECTED'">
              <h5 class="font1-3em lineH-1-8">
                Identificamos que você já está conectado ao WhatsApp, aguargue enquanto lhe redirecionamos para o painel!
              </h5>
            </div>
            </div>
            <div class="col-12 col-lg-6 mt-4 mb-4">
              <h1 class="text-center" v-if="loading">Carregando...</h1>
              <h1 class="text-center">{{status}}</h1>
              <img v-if="status =='QRCODE'" :src="this.qrcode" class="d-block mx-auto">
            </div>
          </div>
        </div>
      </div>
    </div>
</template>

<style>
  .font1-3em {
    font-size: 1.3em;
  }
  .lineH-1-8 {
    line-height: 1.8sem;
  }
  .linheH-2-3 {
    line-height: 2.3em;
  }
</style>

<script>
  export default {
    data() {
      return {
        urlLogin: '/painel/whatsapp/qrcode-login-update',
        qrcode: "",
        status: "",
        loading: true,
      }
    },
    props: ['qrcodeOrStatus'],
    methods: {
      loginValidate: function loginValidate(){
        var self = this;
        axios
          .get('/painel/whatsapp/qrcode-login-update').then(function(response){
            self.qrcode = response.data.qrcode;
            self.status = response.data.status;
            if(self.status == "CONNECTED"){
              // Se conectado limpa o sharaeClosedCount, para o interval e redireciona para o painel
                localStorage.removeItem("sharaeClosedCount");
                clearInterval(self.interval);
                self.redirectPage("/painel", 3000);
            }else if(self.status == 'CLOSED'){
              // Se o status é closed recarrega a página e adiciona +1 no localstorage do usuário, quando atingir 3, não recarrega mais automaticamente
              var increments = parseInt(localStorage.getItem("sharaeClosedCount"));
              if(increments <= 2){
                localStorage.setItem("sharaeClosedCount", ++increments);
                self.reloadPage(3000);
              }else if(!increments) {
                localStorage.setItem("sharaeClosedCount", 0);
              }else {
                  clearInterval(self.interval);
              }
            }
          })
          .finally(() => {
            this.loading = false;
              // console.log('final');
            })
          .catch(errors => {
           // console.log(errors);
          });
      },
      redirectPage(page, seconds){
        setTimeout(function(){
          window.location.href = page;
        }, seconds);
      },
      reloadPage(seconds){
        setTimeout(function(){
          location.reload();
        }, seconds);
      }
    },
    beforeDestroy: function () {
      this.loginValidate.destroy();
  },
    mounted() {
      var jsQrCode = JSON.parse(this.qrcodeOrStatus);
      if(jsQrCode.qrcode != null){
        this.qrcode = jsQrCode.qrcode;
      }
      this.status = jsQrCode.status;
      if(this.status == 'CONNECTED'){
        // Se está conectado, remove o SharaeClosedCount,
        // Interrompe o loading e redireciona para o painel após 3s
        localStorage.removeItem("sharaeClosedCount");
        this.loading = false;
        this.redirectPage('/painel', 3000);
      }
      this.interval = setInterval(() => this.loginValidate(), 5000);
    }
}
</script>
