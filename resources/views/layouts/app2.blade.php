<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>Sistema de Gestão Agrícola</title>

        <!--Bootstrap-->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <!--styles feitos a mão-->
        <link rel="stylesheet" href="{{ asset('/css/styles.css') }}">

        <!--Bootstrap scripts-->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    </head>
    <body>
        @include('incluir.navbar')
        @include('incluir.lateral2')

        <div style="min-height: 1250px !important"class="container">
            @yield('content')
        </div>

    </body>

    <footer>
        <div class="container-fluid rodape" aling="center">
            <div class="pt-5">
                <p class="texto-footer">
                  Ínicio • SGA • Sobre
                </p>
            </div>
            <div class="container py-4 border-top border-light">
                <div class="row py-2">
                    Desenvolvido por:
                    <div class="col-sm">
                        <a href="http://lmts.uag.ufrpe.br/" target="_blank">
                            <img class="img-thumbnail rounded float-left imagem" src="{{ asset('images/lmts.png') }}" alt="">
                        </a>
                    </div>
                    Apoio:
                    <div class="col-sm">
                        <a href="http://ufape.edu.br/" target="_blank">
                            <img class="img-thumbnail rounded float-left imagem" src="{{ asset('images/ufape.png') }}" alt="">
                        </a>
                    </div>
                    <div class="col-sm">
                        Rede Sociais:
                        <div class="row">
                            <div class="col-sm">
                                <a href="https://www.facebook.com/LMTSUFAPE/" target="_blank">
                                    <img class="img-thumbnail my-5 imagem redesocial" src="{{ asset('images/facebook.png') }}" alt="">
                                </a>
                            </div>
                            <div class="col-sm">
                                <a href="https://www.instagram.com/lmts_ufape/" target="_blank">
                                    <img class="img-thumbnail my-5 imagem redesocial" src="{{ asset('images/instagram.png') }}" alt="">
                                </a>
                            </div>
                            <div class="col-sm">
                                <a href="" target="_blank">
                                    <img class="img-thumbnail my-5 imagem redesocial" src="{{ asset('images/twitter.png') }}" alt="">
                                </a>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</html>
