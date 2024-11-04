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
    </head>

    <body>
        <nav class="navbar navbar-expand-lg bg-dark border-bottom border-body" data-bs-theme="dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('index') }}">
                    <img src="{{ asset('template/img/logo_branca.png') }}" alt="{{ env('APP_NAME') }}" width="90" height="36" class="d-inline-block align-text-top">
                    {{ env('APP_NAME') }}
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="{{ route('index') }}">Início</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('ecommerce') }}">Produtos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Minhas compras</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled" aria-disabled="true">Acessar Questões</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="container-fluid">
            @yield('ecommerce')
        </main>

        <nav class="navbar bg-dark border-bottom border-body text-center" data-bs-theme="dark">
            <p class="text-light mx-auto">
                © Copyright Foco Na Divisa. Todos os direitos reservados <br>
                Desenvolvido por <a href="https://expressoftwareclub.com/">Express Software Club</a>
            </p>
        </nav>
        
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
        <script>
            @if(session('error'))
                Swal.fire({
                    title: 'Erro!',
                    text: '{{ session('error') }}',
                    icon: 'error',
                    timer: 2000
                })
            @endif

            @if(session('info'))
                Swal.fire({
                    title: 'Atenção!',
                    text: '{{ session('info') }}',
                    icon: 'info',
                    timer: 3000
                })
            @endif
            
            @if(session('success'))
                Swal.fire({
                    title: 'Sucesso!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    timer: 2000
                })
            @endif
        </script>
    </body>
</html>