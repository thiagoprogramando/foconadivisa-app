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
        <section class="section d-flex flex-column align-items-center justify-content-center">
          <div class="row justify-content-center">
            <div class="col-12 col-sm-12 col-md-8 col-lg-5">
              <div class="card card-login mb-3">
                <div class="card-body mt-0">
                  <div class="d-flex justify-content-center">
                    <a href="{{ route('login') }}" class="logo d-flex align-items-center w-auto">
                      <img src="{{ asset('template/img/logo_preta.jpeg') }}" alt="{{ env('APP_NAME') }}">
                    </a>
                  </div>
                  <div class="pb-2">
                      <h5 class="card-title text-center pb-0 fs-4">Olá, {{ $greeting }}!</h5>
                      <p class="text-center small">Faça login para ter acesso aos benefícios da sua conta.</p>
                  </div>
                  <form action="{{ route('logon') }}" method="POST" class="row g-2">
                      @csrf
                      <div class="col-12">
                        <input type="email" name="email" class="form-control" placeholder="E-mail:" required>
                      </div>
                      <div class="col-10 col-sm-10 col-md-11 col-lg-11">
                        <input type="password" name="password" id="password" class="form-control pe-5" placeholder="Senha:" required>
                      </div>     
                      <div class="col-2 col-sm-2 col-md-1 col-lg-1">
                        <button type="button" id="togglePassword" class="btn bg-transparent">
                            <i id="toggleIcon" class="ri-eye-line"></i>
                        </button>
                      </div>               
                      <div class="col-12">
                        <button type="submit" class="btn btn-dark w-100">Acessar</button>
                        <a href="{{ route('ecommerce') }}" class="btn btn-outline-dark w-100 mt-2">Ver Loja</a>
                      </div>
                      <div class="col-12 text-center">
                        <p class="small mb-0">Não tem uma conta? <a href="{{ route('cadastro') }}">Criar Conta</a></p>
                          ou
                        <p class="small mb-0"><a href="{{ route('recuperar-conta') }}">Recuperar conta</a></p>
                      </div>
                  </form>
                </div>
              </div>

              <div class="credits text-white text-center">
                  Desenvolvido por <a href="https://expressoftwareclub.com/">Express Software Club</a>
              </div>
            </div>
          </div>
        </section>
      </div>
    </main>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
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

        document.getElementById('togglePassword').addEventListener('click', function (e) {
          
          const passwordField = document.getElementById('password');
          const toggleIcon = document.getElementById('toggleIcon');
          const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
          passwordField.setAttribute('type', type);


          if (type === 'password') {
              toggleIcon.classList.remove('ri-eye-off-line');
              toggleIcon.classList.add('ri-eye-line');
          } else {
              toggleIcon.classList.remove('ri-eye-line');
              toggleIcon.classList.add('ri-eye-off-line');
          }
      });
    </script>
  </body>
</html>