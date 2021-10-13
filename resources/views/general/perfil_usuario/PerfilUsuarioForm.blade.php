
{{ Form::open(['onsubmit' => 'return false', 'id' =>'form']) }}

<div class="layout-main d-flex flex-column ml-md-5 mr-md-5 pt-0 card ct-card ">
    <div class="panel-heading">

        @if ($action == 'add')
            <div class="ct-heading card-header bg-secondary text-white font-weight-bold text-uppercase">Novo Perfil de Usuário</div>
        @elseif ($action == 'edit')
            <div class="ct-heading card-header bg-secondary text-white font-weight-bold text-uppercase">Edição de Perfil de Usuário</div>
        @elseif ($action == 'view')
            <div class="ct-heading card-header bg-secondary text-white font-weight-bold text-uppercase">Visualização de Perfil de Usuário</div>
        @endif

    </div>

    <div class="card-body bg-white">
        <div class="row flex-auto">
            <div class="form-group col-md-2">
                {{Form::label('Código*',null,['class'=>'control-label'])}}
                {{Form::text('codigo', ($action == 'add' )?'*Novo Registro':$perfil->id,['class'=>'disableVisualize form-control', 'readonly'])}}
            </div>

            <div class="form-group col-md-10">
                {{Form::label('Nome*',null,['class'=>'control-label'])}}
                {{Form::text('nome', isset($perfil)?$perfil->nome:null,['class'=>'disableVisualize form-control', 'id' =>'nome'])}}
            </div>

            <div class="form-group col-md-12">
                {{Form::label('Descricao',null,['class'=>'control-label'])}}
                {{Form::textarea('descricao', isset($perfil)?$perfil->descricao:null,['class'=>'disableVisualize form-control', 'id' =>'descricao'])}}
            </div>



        </div>




        @if ($action == 'add' || $action == 'edit')

            <button id="btn-add-edit"  class="btn btn-primary float-right" style="margin-top: 40px;">
                <i class="fa fa-btn fa-envelope"></i>
                Salvar
            </button>


        @elseif ($action == 'view')
            <a href='{{route('perfil_usuario.GridView')}}' class="btn btn-secondary float-right viaAjaxPost" style="margin-top: 40px;">
                <i class="fa fa-btn fa-backward"></i>
                Voltar
            </a>
        @endif

    </div>
</div>

{!!Form::close()!!}
<script>
    $(document).ready(function () {


        //Ação do Botão ADD/EDIT
        $('#btn-add-edit').on('click', function(){

            AjaxAddEdit();
        });



        //FIM DO TYPEAHEAD FORNECEDOR
        function AjaxAddEdit(){

            const nome = $('#nome').val();
            const descricao = $('#descricao').val();

            if (!nome){
                msg('Preencha o formulário corretamente e tente realizar a operação novamente<br>Os campos marcados com asterisco são Obrigatórios',false,'warning');
                return;
            }


            let url = '';
            let method = 'POST';


            @if ($action == 'add')
                url =  "{{route('perfil_usuario.store')}}",
                method = 'POST';
            @elseif($action == 'edit')
                url =  "{{route('perfil_usuario.update',[$perfil->id])}}";
                method = 'PUT'
            @endif


            doPostAjaxCall(
                url,
                {
                    nome: nome,
                    descricao:descricao,
                    _method: method


                },
                function(resposta){
                    msg(resposta.message, resposta.success)
                    if (resposta.success){
                        getView('{{route('perfil_usuario.GridView')}}', []);
                    }

                },
                function(resposta){
                    //msg(resposta.responseText,false,'log');
                }
            )

        }



        @if ($action == 'edit' || $action == 'view')

        //Reload Exceptions

        @endif

        @if ($action == 'view')
            disableForm();
        @endif





    });
</script>




