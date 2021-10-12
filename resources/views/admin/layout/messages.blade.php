<script>

    function msg(message, success, type) {


        if (isJSONString(message)){
            error_msg = '<b>A operação não pôde ser realizada porque: </b><br><br>';
            error = $.parseJSON(message);
            for (var key in error) {
                error_msg += '<span style="color: red !important;"><b>*</b></span>'+error[key]+'<br>';
            }
            message = error_msg;
        }

        if (typeof(success) == 'undefined' && typeof(type) == 'undefined' && typeof(message) != 'undefined') {

            w2popup.open({
                title: '<b>INFORMAÇÃO</b>',
                body: '<div class="custom-msg-body"><img class="icon-alert" src="{{asset('assets/img/001-help.svg')}}" alt="Mensagem Padrão">'+message+"</div>",
                buttons   : '<button class="w2ui-btn" onclick="w2popup.close();">OK</button> '
            });
            return;
        }

        //Mensagens "Warning" podem ser enviados tanto para erro quanto para sucesso
        if (type == 'warning' ){

            w2popup.open({
                title: '<b>ATENÇÃO</b>',
                body: '<div class="custom-msg-body"><img class="icon-alert" src="{{asset('assets/img/004-information.svg')}}" alt="Mensagem de Atenção">'+message+"</div>",
                buttons   : '<button class="w2ui-btn" onclick="w2popup.close();">OK</button> '
            });
            return;
        }

        //Mensagens com Type "Danger" são sempre erros (para manter a compatibilidade"
        if (type == 'danger' ){

            w2popup.open({
                title: '<b>ERRO</b>',
                body: '<div class="custom-msg-body"><img class="icon-alert" src="{{asset('assets/img/002-cancel.svg')}}" alt="Mensagem de Erro">'+message+"</div>",
                buttons   : '<button class="w2ui-btn" onclick="w2popup.close();">OK</button> '
            });
            return;
        }

        //Se sucessoigual a false, exibe mensagem de erro padrão
        if (success == false){

            if (type == 'log'){
                w2popup.open({
                    title: '<b>ERRO</b>',
                    width: '1000',
                    height: '700',
                    /*width: -1,
                    height: -1,*/
                    body: '<div class="custom-msg-body"><img class="icon-alert" src="{{asset('assets/img/002-cancel.svg')}}" alt="Mensagem de Erro">'+message+"</div>",
                    buttons   : '<button class="w2ui-btn" onclick="w2popup.close();">OK</button> '
                });
                return;
            }

            w2popup.open({
                title: '<b>ERRO</b>',
                body: '<div class="custom-msg-body"><img class="icon-alert" src="{{asset('assets/img/002-cancel.svg')}}" alt="Mensagem de Erro">'+message+"</div>",
                buttons   : '<button class="w2ui-btn" onclick="w2popup.close();">OK</button> '
            });
            return;

        }

        //início do tratamento de mensagens de sucesso
        if (success == true){


            if (!type){
                w2popup.open({
                    title: '<b>SUCESSO</b>',
                    body: '<div class="custom-msg-body"><img class="icon-alert" src="{{asset('assets/img/003-checked.svg')}}" alt="Mensagem de Sucesso">'+message+"</div>",
                    buttons   : '<button class="w2ui-btn" onclick="w2popup.close();">OK</button> '
                });
                return;
            }

            if (type == 'info'){
                w2popup.open({
                    title: '<b>INFORMAÇÃO</b>',
                    body: '<div class="custom-msg-body"><img class="icon-alert" src="{{asset('assets/img/001-help.svg')}}" alt="Mensagem Padrão">'+message+"</div>",
                    buttons   : '<button class="w2ui-btn" onclick="w2popup.close();">OK</button> '
                });
                return;
            }


        }


    }






</script>