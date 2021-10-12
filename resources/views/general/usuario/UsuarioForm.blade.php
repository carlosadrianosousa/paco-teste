{{ Form::open(['onsubmit' => 'return false', 'id' =>'form']) }}

<div class="layout-main d-flex flex-column ml-md-5 mr-md-5 pt-0 card ct-card ">
    <div class="panel-heading">

        @if ($action == 'add')
            <div class="ct-heading card-header bg-secondary text-white font-weight-bold text-uppercase">Novo Usuário</div>
        @elseif ($action == 'edit')
            <div class="ct-heading card-header bg-secondary text-white font-weight-bold text-uppercase">Edição de Usuário</div>
        @elseif ($action == 'view')
            <div class="ct-heading card-header bg-secondary text-white font-weight-bold text-uppercase">Visualização de Usuário</div>
        @endif

    </div>

    <div class="card-body bg-white">
        <div class="row flex-auto">
            <div class="form-group col-md-2">
                {{Form::label('Código*',null,['class'=>'control-label'])}}
                {{Form::text('codigo', ($action == 'add' )?'*Novo Registro':$usuario->id,['class'=>'disableVisualize form-control', 'readonly'])}}
            </div>

            <div class="form-group col-md-10">
                {{Form::label('Nome*',null,['class'=>'control-label'])}}
                {{Form::text('nome', isset($usuario)?$usuario->name:null,['class'=>'disableVisualize form-control', 'id' =>'nome'])}}
            </div>


            <div class="form-group col-md-6">
                {{Form::label('Perfil de Usuário*',null,['class'=>'control-label'])}}
                <select class="form-control" id="perfil">
                    @foreach ($perfis as $obj)
                        <option value="{{$obj->id}}">{{$obj->nome}}</option>
                    @endforeach
                </select>

            </div>

            <div class="form-group col-md-6">
                {{Form::label('Email/Login*',null,['class'=>'control-label'])}}
                {{Form::text('email', isset($usuario)?$usuario->email:null,['class'=>'disableVisualize form-control','type' => 'email', 'id' =>'email'])}}
            </div>

            <div class="form-group col-md-3">
                <label for="password">Senha*</label>
                <input type="password" class="form-control disableVisualize" id="password" placeholder="******">
            </div>

            <div class="form-group col-md-3">
                <label for="password">Confirmar Senha*</label>
                <input type="password" class="form-control disableVisualize" id="password_confirm" placeholder="******">
            </div>

            <div class="form-group col-md-2">
                {{Form::label('Status*',null,['class'=>'control-label'])}}
                <select class="form-control disableVisualize" id="ativo">
                    <option value="1">Ativo</option>
                    <option value="0">Inativo</option>
                </select>

            </div>





        </div>



        @if ($action == 'add' || $action == 'edit')

            <button id="btn-add-edit"  class="btn btn-primary float-right" style="margin-top: 40px;">
                <i class="fa fa-btn fa-envelope"></i>
                Salvar
            </button>


        @elseif ($action == 'view')
            <a href='{{route('usuario.GridView')}}' class="btn btn-secondary float-right viaAjaxPost" style="margin-top: 40px;">
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

            nome = $('#nome').val();
            perfil_id = $('#perfil').val();
            email = $('#email').val();
            senha = $('#password').val();
            senha_confirm = $('#password_confirm').val();
            ativo = $('#ativo').val();
            cpf = $('#cpf').val();
            nomem = $('#nomem').val();
            datan = $('#datan').val();

            if (!nome || !perfil_id || !email){
                msg('Preencha o formulário corretamente e tente realizar a operação novamente<br>Os campos marcados com asterisco são Obrigatórios',false,'warning');
                return;
            }


            if (senha != senha_confirm){
                msg('As senhas não conferem.',false,'warning');
                return;
            }

            url = '';
            method = '';
            @if ($action == 'add')
                url =  "{{route('usuario.store')}}";
                method = 'POST';
            @elseif ($action == 'edit')
                url =  "{{route('usuario.update',[$usuario->id])}}";
                method = 'PUT';
            @endif

            doPostAjaxCall(
                url,
                {
                    _method: method,
                    nome: nome,
                    perfil_id:perfil_id,
                    email:email,
                    senha:senha,
                    senha_confirm:senha_confirm,
                    ativo: ativo,


                },
                function(resposta){
                    msg(resposta.message, resposta.success)
                    if (resposta.success){
                        getView('{{route('usuario.GridView')}}', []);
                    }

                },
                function(resposta){
                    //msg(resposta.responseText,false,'log');
                }

            )




        }



        @if ($action == 'edit' || $action == 'view')

        //Reload Exceptions
        $('#perfil').val('{{$usuario->perfil_id}}');
        $('#ativo').val('{{$usuario->ativo}}');
        @endif

        @if ($action == 'view')
            disableForm();
        @endif





    });
</script>






