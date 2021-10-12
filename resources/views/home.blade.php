<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">


    <script type="text/javascript" src="components/jquery/jquery.min.js"></script>
<!--    <script type="text/javascript" src="js/jquery.mask.min.js"></script>-->



</head>


<body style="display: none !important;">
<!-- Overlay -->
<div id="overlay" class="preloading-effect h-100 w-100 d-none justify-content-center align-items-center"
     style="z-index: 999998 !important; "></div>

<!-- Preloading -->
<div id="mask" name='mask' class="preloading-effect h-100 w-100 d-none justify-content-center align-items-center">
    <img src="{{asset('assets/img/preloading-img.svg')}}" alt="Carregando...">
</div>

@include('admin.layout.header', ['sidemenu' => true])



<div id="sidr">
    <h4 class="d-inline-block w-100 menu_title">MENU DO USUÁRIO
        <div class="close-menu float-right pt-md-2" id="closeAction">
            <i class="fa fa-times fa-fw"></i>
        </div>
    </h4>

    <!-- Ítens de Menu-->
    <ul class="side-menu">
        <li>
            <a data-toggle="collapse" href="#multiCollapseUsuarios" aria-expanded="false"
               aria-controls="multiCollapseUsuarios">
                <i class="fa fa-users fa-fw mr-2"></i>Usuários
                <i class="fa fa-chevron-down trigger-right align-content-center align-middle"></i>

            </a>
            <div class="collapse multi-collapse" id="multiCollapseUsuarios">
                <ul class="side-submenu">
                    <li><a href="{{route('usuario.GridView')}}" class="viaAjaxPost">Usuários do Sistema</a></li>
                    <li><a href="{{route('perfil_usuario.GridView')}}" class="viaAjaxPost">Perfil de Usuário</a></li>
                </ul>
            </div>
        </li>

        <li>
            <a data-toggle="collapse" class="viaAjaxPost" href="{{route('usuario.EditView',[\Auth::user()->id])}}" aria-expanded="false">
                <i class="fa fa-user fa-fw mr-2"></i>Meu Perfil
            </a>
        </li>

        <li>
            <a data-toggle="collapse" class="viaAjaxPost" href="#" aria-expanded="false">
                <i class="fa fa-refresh fa-fw mr-2"></i>Conversão Monetária
            </a>
        </li>



    </ul>
</div>

<div class="layout-main  d-flex flex-column bg-white">
    <div class="container-fluid pt-4">
        <!-- Container que envolve o Grid -->
        <div class="row" class="">
            <div class="col mt-3" id="contentView">
                @yield('content')
            </div>
            @include('admin.layout.footer')
        </div>


    </div>
    @include('admin.layout.footer')
</div>


<link rel="stylesheet" href="components/bootstrap/css/bootstrap.css">
<link rel="stylesheet" href="components/w2ui/w2ui-1.5.rc1.min.css">
<link rel="stylesheet" href="components/sidr/stylesheets/jquery.sidr.light.css">
<link rel="stylesheet" href="components/font-awesome/css/all.css">
<link rel="stylesheet" href="css/layout.css">


<script src="components/sidr/jquery.sidr.min.js"></script>
<script src="components/bootstrap/js/bootstrap.min.js"></script>


<script>
    $(document).ready(function(){
        $('body').show();
        setMaskOnAjaxPost();
        setEventsAfterAjax();
        eventExecuteView();
        setShortcuts();

        setSidrEvents();

        //Carrega o arquivo Locale da Bibliote W2UI (em PORTUGUÊS)
        w2utils.locale('{{asset('components/w2ui/w2ui-pt-br.json')}}');

    });

</script>

@include('admin.layout.messages')
<script src="components/w2ui/w2ui-1.5.rc1.min.js"></script>
<script src="{{asset('js/adminjs.js')}}"></script>
<script src="https://igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js"></script>

</body>
</html>





