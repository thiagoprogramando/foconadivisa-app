<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">

        <title>{{ env('APP_NAME') }} - {{ env('APP_DESCRIPTION') }}</title>
        <meta content="{{ env('META_DESCRIPTION') }}" name="description">
        <meta content="{{ env('META_KEYWORDS') }}" name="keywords">

        <link href="{{ asset('template/img/favicon.png') }}" rel="icon">
        <link href="{{ asset('template/img/favicon.png') }}" rel="apple-touch-icon">

        <link href="https://fonts.gstatic.com" rel="preconnect">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
        
        <link href="{{ asset('template/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('template/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
        <link href="{{ asset('template/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
        <link href="{{ asset('template/vendor/quill/quill.snow.css') }}" rel="stylesheet">
        <link href="{{ asset('template/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
        <link href="{{ asset('template/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
        <link href="{{ asset('template/vendor/simple-datatables/style.css') }}" rel="stylesheet">
        <link href="{{ asset('template/css/style.css') }}" rel="stylesheet">

        <style>
            * {
              width: 100%;
              height: auto;
            }
      
            body {
              background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('{{ asset('template/img/background/bg_military_us.jpg') }}');
              background-size: 100;
              background-repeat: no-repeat;
              background-position: center;
              min-height: 100vh;
            }
        </style>
    </head>
    <body>

        <main>
            <div class="container">
                <section class="section d-flex flex-column align-items-center justify-content-center py-4">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-12 col-sm-12 col-md-8 col-lg-5">
                                <div class="card card-login mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-center">
                                            <a href="{{ route('cadastro') }}" class="logo d-flex align-items-center w-auto">
                                                <img src="{{ asset('template/img/logo_preta.jpeg') }}" alt="{{ env('APP_NAME') }}">
                                            </a>
                                        </div>
                                        <div class="pb-2">
                                            <h5 class="card-title text-center pb-0 fs-4">Faça parte!</h5>
                                            <p class="text-center small">Preencha seus dados para receber benefícios.</p>
                                            @if ($errors->any())
                                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                    <i class="bi bi-exclamation-octagon me-1"></i>
                                                    @foreach ($errors->all() as $error)
                                                        {{ $error }}
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                        <form action="{{ route('registrer') }}" method="POST" class="row g-3">
                                            @csrf
                                            <div class="col-12">
                                                <input type="text" name="name" class="form-control" placeholder="Nome:" required>
                                            </div>
                                            <div class="col-12">
                                                <input type="email" name="email" class="form-control" placeholder="E-mail:" required>
                                            </div>
                                            <div class="col-12">
                                                <input type="password" name="password" class="form-control" placeholder="Senha:" required>
                                            </div>
                                            <div class="col-12">
                                                <input type="number" name="meta" class="form-control" placeholder="Qual sua meta de Questões?" required>
                                            </div>
                                            <div class="col-12">
                                                <button class="btn btn-dark w-100" type="submit">Cadastrar-me</button>
                                            </div>
                                            <div class="col-12">
                                                <p class="small mb-0">Já tem uma conta? <a href="{{ route('login') }}">Acessar</a></p>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="credits text-white text-center">
                                    Desenvolvido por <a href="https://expressoftwareclub.com/">Express Software Club</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </main>

        <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

        <script src="{{ asset('template/vendor/apexcharts/apexcharts.min.js') }}"></script>
        <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('template/vendor/chart.js/chart.umd.js') }}"></script>
        <script src="{{ asset('template/vendor/echarts/echarts.min.js') }}"></script>
        <script src="{{ asset('template/vendor/quill/quill.min.js') }}"></script>
        <script src="{{ asset('template/vendor/simple-datatables/simple-datatables.js') }}"></script>
        <script src="{{ asset('template/vendor/tinymce/tinymce.min.js') }}"></script>
        <script src="{{ asset('template/vendor/php-email-form/validate.js') }}"></script>
        <script src="{{ asset('template/js/main.js') }}"></script>
        <script src="{{ asset('template/js/jquery.js') }}"></script>
        <script src="{{ asset('template/js/sweetalert.js') }}"></script>
    </body>
</html>