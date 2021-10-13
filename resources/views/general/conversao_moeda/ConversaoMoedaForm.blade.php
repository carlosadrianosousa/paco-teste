
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
                        <span class="count-numbers" id="count-cache">0</span>
                        <span class="count-name">Total requisições Cache</span>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card-counter info">
                        <i class="fa fa-code-pull-request"></i>
                        <span class="count-numbers" id="count-request">0</span>
                        <span class="count-name">Total de Requisições à API</span>
                    </div>
                </div>
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


    });
</script>







