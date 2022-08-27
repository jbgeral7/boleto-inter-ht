<template>
    <div>
        <li class="nav-item d-none d-sm-inline-block">
           <i class="text-success fas fa-circle" v-if="infoStatus.status == true" title="Online no WhatsApp"> ONLINE WHATSAPP</i>
           <i class="text-danger fas fa-circle" v-if="infoStatus.status == false && revaliditing == false" title="Seção online"> Offline WHATSAPP
               <a v-on:click="revalidateSession" class="text-success" href="#"><strong>RECONECTAR</strong></a>
               </i>
           <i class="text-warning fas fa-circle" v-if="infoStatus.status == 3"> Atenção</i>
           <i class="text-warning fas fa-circle" v-if="revaliditing"> Reconectando, isso pode demorar até 2 minutos</i>
      </li>
    </div>
</template>

<script>
  export default {
    data() {
      return {
        urlCheckStatus: '/painel/whatsapp/check-status',
        infoStatus: false,
        infoBattery: false,
        generateUrl: '',
        revaliditing: false,
        expireValidated: '',
      }
    },
    props: ['revalidateUrl'],
    methods: {
      revalidateSession: function revalidateSession(){
          var self = this;
          sessionStorage.setItem('expireValidated', self.expireValidated);
          axios.get(self.generateUrl).then(function(response){
              self.revaliditing = true;
            alert('Inicializando, isso pode levar até 2 minutos');
        }).catch(function(error){
          alert('Ocorreu um erro ao tentar revalidar, a página será recarregada automaitcamente. Por favor, tente novamente');
          document.location.reload(true);
        })
      },
      checkStatus: function checkStatus(){
        const requestStatus = axios.get(this.urlCheckStatus);
        axios
          .all([requestStatus])
          .then(
            axios.spread((...responses) => {
              const responseStatus = responses[0]['data'];
              this.infoStatus = responseStatus;
            })
          ).finally(() => {
              if(this.infoStatus.status){
                this.revaliditing = false;
              }
              else if(this.infoStatus.status == false){
                document.title = "Sessão Offline";
              }else{
                document.title = "Ocorreu um erro";
              }
            })
          .catch(errors => {
            document.title = "Erro";
          });
      }
    },
    beforeDestroy: function () {
    	this.checkStatus.destroy();
	},
    mounted() {
      this.generateUrl = '/painel/whatsapp/';
      this.checkStatus();
      this.expireValidated = parseInt(this.revalidateUrl) + parseInt(2);
      if(typeof sessionStorage.getItem('expireValidated') !== undefined && parseInt(sessionStorage.getItem('expireValidated')) > parseInt(this.revalidateUrl)){
        this.revaliditing = true;
      }else {
          this.revaliditing = false;
          sessionStorage.removeItem('expireValidated')
      }
    }
}
</script>
