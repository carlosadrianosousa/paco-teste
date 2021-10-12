<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="author" content="{{config('app.readable_name')}} - Login">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{config('app.readable_name')}} - Login</title>
    <link rel="shortcut icon" href="../favicon.ico">


    {{--<link defer rel="stylesheet" href="components/bootstrap/css/bootstrap.css">
    <link defer rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/open-iconic/1.1.0/font/css/open-iconic-bootstrap.min.css" />
    <link defer rel="stylesheet" href="css/layout.css">--}}

    <link defer rel="stylesheet" href="components/bootstrap/css/bootstrap.css">
    <link defer rel="stylesheet" href="css/layout.css">
    <link defer rel="stylesheet" href="css/app.css">
    <link defer rel="stylesheet" href="components/font-awesome/css/all.css">

</head>

<body>
<!-- start-Conteúdo do Login -->
<div class="layout-login h-100 d-flex flex-column">
    <div class="container-fluid d-flex flex-column justify-content-center align-items-center">

        <div class="row col-lg-4 col-md-6">
            <div class="col">

                <!-- start-Formulário de Login -->
                <div class="card align-middle">
                    <div class="card-body">

                        <div class="row w-100 mb-5 mt-5">
                            <div class="col text-center">
                                <img src="{{asset('assets/logo/paco_logo.png')}}" alt="">
                            </div>
                        </div>

                        <form class="mt-5" action="/Login" method="POST">

                            {{ csrf_field() }}


                            <div class="input-group mb-3">

                                <div class="input-group-prepend">
                                    <span class="input-group-text fa fa-user"></span>
                                </div>
                                <input type="email" name="email" type="text" class="form-control" placeholder="Informe o e-mail" aria-label="Usuário">
                            </div>

                            <div class="input-group mb-3">

                                <div class="input-group-prepend">
                                    <span class="input-group-text fa fa-lock"></span>
                                </div>
                                <input type="password" name="password" type="text" class="form-control" placeholder="Informe a senha" aria-label="Usuário">
                            </div>




                            {{--<div class="form-group">
                                <label for="user">Usuário</label>
                                <div class="input-group">
                                    <span class="input-group" id="basic-addon1"><i class="fa fa-user"></i></span>
                                    <input type="email" class="form-control" name="email" id="user">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password">Senha</label>
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1"><i class="fa fa-lock"></i></span>
                                    <input type="password" class="form-control" name="password" id="password">
                                </div>
                            </div>--}}
                            <hr>

                            <button type="submit" class="btn btn-primary btn-block">ENTRAR</button>
                            @if(isset($error))
                                <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                                    <strong>{{$error}}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </strong>
                                </div>

                            @endif
                        </form>
                    </div>
                </div>
                <!-- end -->

            </div>
        </div>
    </div>
    <!-- end -->


</div>
@include('admin.layout.footer')
<!-- end -->

{{--<script src="js/app.js"></script>--}}
<script defer src="components/jquery/jquery.min.js"></script>
<script defer src="components/bootstrap/js/bootstrap.min.js"></script>




</body>

</html>
