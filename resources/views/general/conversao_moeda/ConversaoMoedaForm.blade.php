
{{ Form::open(['onsubmit' => 'return false', 'id' =>'form']) }}

<div class="layout-main d-flex flex-column ml-md-5 mr-md-5 pt-0 card ct-card ">
    <div class="panel-heading">
        <div class="ct-heading card-header bg-secondary text-white font-weight-bold text-uppercase">Conversão Monetária</div>
    </div>

    <div class="card-body bg-white">

        <div class="container">
            <div class="row pl-0 ml-0">
                <div class="col-md-4">
                    <div class="row">
                        <div class="form-group col-md-5">
                            <select id="moeda_origem_combo" class="form-control disableVisualize">
                                <option value="">ORIGEM</option>
                                @foreach ($moedas as $obj)
                                    <option value="{{$obj->id}}">{{$obj->id}} </option>
                                @endforeach

                            </select>
                        </div>
                        <div class="form-group col-md-7">
                            {{Form::text('valor_origem', null,['class'=>'disableVisualize form-control', 'id' => 'valor_origem','placeholder' => 'Valor Origem'])}}
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-5">
                            <select id="moeda_destino_combo" class="form-control disableVisualize">
                                <option value="">DESTINO</option>
                                @foreach ($moedas as $obj)
                                    <option value="{{$obj->id}}">{{$obj->id}} </option>
                                @endforeach

                            </select>
                        </div>
                        <div class="form-group col-md-7">
                            {{Form::text('valor_destino',"0,000000",['class'=>'disableVisualize form-control', 'id' => 'valor_destino','placeholder' => 'Valor Destino','readonly'])}}
                        </div>
                    </div>

                    <div class="row text-center ml-sm-0 mr-sm-0 pl-sm-0 pr-sm-0" id="div-gerar-remessa">
                        <div class="col-md-12 text-center">
                            <button id="btn-convert" class="btn  btn-secondary">
                                <i class="fa fa-btn fa-refresh"></i>
                                Converter
                            </button>
                        </div>
                    </div>

                </div>

                <div class="col-md-2">
                    <div class="row flex-auto text-center align-content-center">
                        <div class="form-group col-md-12">
                            {{--{{Form::label('DATA',null,['class'=>'control-label font-weight-bold'])}}--}}
                            {{Form::text('date_ref', $cur_date->format('d/m/Y'),['class'=>'disableVisualize form-control datepicker', 'id' => 'ref_date','placeholder' => 'DATA'])}}
                        </div>
                        <div class="col-md-12">
                            {{Form::label('UTILIZAR CACHE?',null,['class'=>'control-label font-weight-bold'])}}
                            <input name="enable-cache" type="checkbox" id="enable-cache">
                        </div>


                    </div>


                </div>

                <div class="col-md-3">
                    <div class="card-counter success">
                        <i class="fa fa-database"></i>
                        <span class="count-numbers" id="count-cached">{{$cache_info->cached}}</span>
                        <span class="count-name">Total requisições Cache</span>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card-counter info">
                        <i class="fa fa-code-pull-request"></i>
                        <span class="count-numbers" id="count-not-cached">{{$cache_info->not_cached}}</span>
                        <span class="count-name">Total de Requisições à API</span>
                    </div>
                </div>
            </div>
        </div>

        <!--INÍCIO DA GRID DE HISTÓRICOS-->
        <div class="row">
            <div class="col mt-3">
                <div id="gridHistorico" class="w-100 pb-0 mb-0" style="height: 250px !important;"></div>
            </div>
        </div>






    </div>
</div>

{!!Form::close()!!}
<script>
    $(document).ready(function () {


        $("#enable-cache").bootstrapSwitch({
            state: true, //State: true não está funcionando
        });

        $("#enable-cache").prop('checked',true);
        $("#enable-cache").trigger('change');

        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            language: "pt-BR"
        });

        $('#valor_origem').maskMoney({
            precision:2,
            thousands:'.',
            decimal: ',',
            //formatOnBlur: true,
            //reverse: true,
            selectAllOnFocus: true,
            allowEmpty: true
        });

        $('#btn-convert').on('click', function(){
            convert();
        })

        function convert(){

            const ref_date = $('#ref_date').val();
            const moeda_origem = $('#moeda_origem_combo').val();
            const valor_origem = $('#valor_origem').maskMoney('unmasked')[0];
            const moeda_destino = $('#moeda_destino_combo').val();
            const cache = $('#enable-cache').prop('checked')?1:0;

            if (!ref_date || !moeda_origem || !valor_origem || !moeda_destino){
                msg("Informe os campos necessários no formulário.");
                return;
            }

            doPostAjaxCall(
                '{{route('conversao_monetaria.convert')}}',
                {
                    ref_date,
                    moeda_origem,
                    valor_origem,
                    moeda_destino,
                    cache
                },
                function(resposta){
                    if (!resposta.success)
                        msg(resposta.message,false);

                    const data = resposta.data;
                    changeFormState(data.cache.cached,data.cache.not_cached,data.valor_conversao);

                },
                function(resposta){
                    msg(resposta.responseText,false,'log')
                }
            )

        }

        function changeFormState(cached, not_cached,converted_value){

            cached = cached || 0;
            not_cached = not_cached || 0;
            converted_value = converted_value || 0;

            $('#count-cached').html(cached);
            $('#count-not-cached').html(not_cached);
            $('#valor_destino').val(realFormat(converted_value,6,true));
            w2ui['gridHistorico'].reload();


        }




        $().w2destroy("gridHistorico");
        $('#gridHistorico').w2grid({
            name: 'gridHistorico',
            header: 'Histórico de Conversões',
            msgRefresh: 'Atualizando...',
            multiSelect : false,
            recid:'id',
            method: 'GET',
            show: {
                footer: true,
                toolbar: true,
                toolbarAdd: false,
                toolbarDelete: false,
                toolbarEdit: false,
                header: true,
                toolbarColumns: false,
                searchAll: false,
                toolbarInput: false
            },
            columns: [
                { caption: '', size: '20px', attr: 'align=center',info: true},
                { field: 'recid', caption: 'Cód.', size: '100px', sortable: true, attr: 'align=center', type: 'int' },
                { field: 'nome', caption: 'Nome', size: '200px', sortable: true, attr: 'align=center', type: 'text' },
                { field: 'descricao', caption: 'Descrição', size: '800px', sortable: true, resizable: true, type: 'text' },

            ],

            searches: [
                { field: 'id', caption: 'ID', type: 'int' },
                { field: 'nome', caption: 'Nome', type: 'text' },
                { field: 'descricao', caption: 'Descrição', type: 'text' },
            ],

            url: '{{route('conversao_monetaria.listar')}}',

        });


    });
</script>







