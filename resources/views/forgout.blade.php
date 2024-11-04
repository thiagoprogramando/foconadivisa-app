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

        <main>
            <div class="container">
                <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                                <div class="d-flex justify-content-center py-4">
                                    <a href="{{ route('cadastro') }}" class="logo d-flex align-items-center w-auto">
                                        <img src="{{ asset('template/img/logo.png') }}" alt="{{ env('APP_NAME') }}">
                                    </a>
                                </div>

                                @if(empty($code))
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="pt-4 pb-2">
                                                <h5 class="card-title text-center pb-0 fs-4">Recuperação de conta!</h5>
                                                <p class="text-center small">Esqueceu algo? Recupere os dados da sua conta!</p>
                                            </div>

                                            <form action="{{ route('send-recovery') }}" method="POST" class="row g-3">
                                                @csrf
                                                <div class="col-12">
                                                    <input type="email" name="email" class="form-control" placeholder="E-mail:" required>
                                                </div>
                                                <div class="col-12">
                                                    <button class="btn btn-dark w-100" type="submit">Enviar</button>
                                                </div>
                                                <div class="col-12">
                                                    <p class="small mb-0">Já tem uma conta? <a href="{{ route('login') }}">Acessar</a></p>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @else
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="pt-4 pb-2">
                                                <h5 class="card-title text-center pb-0 fs-4">Nova senha!</h5>
                                                <p class="text-center small">Escolha uma nova senha para o seu usuário.</p>
                                            </div>

                                            <form action="{{ route('recovery-password') }}" method="POST" class="row g-3">
                                                @csrf
                                                <div class="col-12">
                                                    <input type="hidden" name="code" class="form-control" placeholder="Código" value="{{ $code }}" required>
                                                </div>
                                                <div class="col-12">
                                                    <input type="text" name="password" class="form-control" placeholder="Nova senha:" required>
                                                </div>
                                                <div class="col-12">
                                                    <input type="text" name="confirmpassword" class="form-control" placeholder="Confirme a senha:" required>
                                                </div>
                                                <div class="col-12">
                                                    <button class="btn btn-dark w-100" type="submit">Redefinir</button>
                                                </div>
                                                <div class="col-12">
                                                    <p class="small mb-0">Já tem uma conta? <a href="{{ route('login') }}">Acessar</a></p>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endif

                                <div class="credits">
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
        <script>
            @if(session('error'))
                  Swal.fire({
                      title: 'Erro!',
                      text: '{{ session('error') }}',
                      icon: 'error',
                      timer: 2000
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