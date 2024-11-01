<!DOCTYPE html>
<html lang="pt-br">

    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">

        <title>{{ env('APP_NAME') }} - {{ env('APP_DESCRIPTION') }}</title>
        <meta content="" name="description">
        <meta content="" name="keywords">

        <link href="{{ asset('template/img/favicon.png') }}" rel="icon">
        <link href="{{ asset('template/img/favicon.png') }}" rel="apple-touch-icon">

        <link href="https://fonts.gstatic.com" rel="preconnect">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

        <link href="{{ asset('template/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('template/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
        <link href="{{ asset('template/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
        <link href="{{ asset('template/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
        <link href="{{ asset('template/vendor/simple-datatables/style.css') }}" rel="stylesheet">
        <link href="{{ asset('template/css/style.css') }}" rel="stylesheet">

        <link href="{{ asset('template/css/tom-select.css') }}" rel="stylesheet">
        <script src="{{ asset('template/js/jquery.js') }}"></script>
        <script src="{{ asset('template/js/tom-select.complete.min.js') }}"></script>
    </head>

    <body @if(!empty($menu) && $menu == 1) class="toggle-sidebar" @endif>

        <header id="header" class="header fixed-top d-flex align-items-center">
            <div class="d-flex align-items-center justify-content-between">
                <a href="{{ route('app') }}" class="logo d-flex align-items-center">
                    <img src="{{ asset('template/img/logo.png') }}" alt="Logo">
                    <span class="d-none d-lg-block">{{ env('APP_NAME') }}</span>
                </a>
                <i class="bi bi-list toggle-sidebar-btn"></i>
            </div>

            <div class="search-bar">
                <form class="search-form d-flex align-items-center" method="GET" action="{{ route('search') }}">
                    <input type="text" name="search" placeholder="Pesquisar" title="Pesquisar">
                    <button type="submit" title="Pesquisar"><i class="bi bi-search"></i></button>
                </form>
            </div>

            <nav class="header-nav ms-auto">
                <ul class="d-flex align-items-center">
                    <li class="nav-item d-block d-lg-none">
                        <a class="nav-link nav-icon search-bar-toggle " href="#">
                            <i class="bi bi-search"></i>
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-bell"></i>
                            <span class="badge bg-dark badge-number">{{ $notifications->count() }}</span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                            <li class="dropdown-header">
                                Você tem {{ $notifications->count() }} novas notificações
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            @foreach ($notifications as $notification)
                                <a href="{{ route('delete-notification', ['id' => $notification->id]) }}">
                                    <li class="notification-item">
                                        {!! $notification->typeLabel() !!}
                                        <div>
                                            <h4>{{ $notification->title }}</h4>
                                            <p>{{ $notification->description }}</p>
                                            <p>{{ $notification->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                    </li>
                                </a>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                            @endforeach

                            <li class="dropdown-footer">
                                <a href="#">Não há mais nada.</a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown pe-3">

                        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                            @if(Auth::user()->photo)
                                <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="Profile" class="rounded-circle">
                            @else
                                <img src="{{ asset('template/img/components/profile.png') }}" alt="Profile" class="rounded-circle">
                            @endif
                            <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::user()->firstName() }} {{ Auth::user()->secondName() }}</span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                            <li class="dropdown-header">
                                <h6>{{ Auth::user()->firstName() }}</h6>
                                <span>{{ Auth::user()->labelPlan->name }}</span>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('perfil') }}">
                                    <i class="bi bi-person"></i>
                                    <span>Perfil</span>
                                </a>
                            </li>
                            <li> <hr class="dropdown-divider"> </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('faq') }}">
                                    <i class="bi bi-question-circle"></i>
                                    <span>Precisa de ajuda?</span>
                                </a>
                            </li>
                            <li> <hr class="dropdown-divider"> </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('tickets') }}">
                                    <i class="ri-alarm-warning-line"></i>
                                    <span>Tickets</span>
                                </a>
                            </li>
                            <li> <hr class="dropdown-divider"> </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Sair</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </header>

        <aside id="sidebar" class="sidebar">
            <ul class="sidebar-nav" id="sidebar-nav">
                <li class="nav-item">
                    <a class="nav-link " href="{{ route('app') }}">
                        <i class="bi bi-grid"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                @if(Auth::user()->type == 1)
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('cadernos') }}">
                        <i class="bi bi-pen"></i>
                        <span>Resolver Questões</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('cadernos') }}">
                        <i class="bi bi-book-half"></i>
                        <span>Meus Cadernos</span>
                    </a>
                </li>
                @endif

                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('minhas-compras') }}">
                        <i class="bi bi-shop"></i>
                        <span>Minhas Compras</span>
                    </a>
                </li>

                @if(Auth::user()->type == 1)
                <li class="nav-heading">Meus Dados</li>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('statistic') }}"><i class="bi bi-file-bar-graph"></i><span>Estátisticas</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('planos') }}"><i class="bi bi-cart"></i><span>Planos</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('pagamentos') }}"><i class="bi bi-arrow-down-square-fill"></i><span>Pendências</span></a>
                </li>
                @endif

                @if(Auth::user()->type == 1)
                    <li class="nav-heading">Gestão</li>
                    <li class="nav-item">
                        <a class="nav-link collapsed" data-bs-target="#components-materiais" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-bookmarks"></i><span>Conteúdo</span><i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <ul id="components-materiais" class="nav-content collapse " data-bs-parent="#sidebar-materiais">
                            <li>
                                <a href="{{ route('conteudos') }}"><i class="bi bi-circle"></i><span>Conteúdos</span></a>
                            </li>
                            <li>
                                <a href="{{ route('topicos') }}"><i class="bi bi-circle"></i><span>Tópicos</span></a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link collapsed" data-bs-target="#components-cart" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-cart-check"></i><span>Planos</span><i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <ul id="components-cart" class="nav-content collapse " data-bs-parent="#sidebar-cart">
                            <li>
                                <a href="{{ route('planos') }}"><i class="bi bi-circle"></i><span>Planos</span></a>
                            </li>
                            <li>
                                <a href="{{ route('vendas') }}"><i class="bi bi-circle"></i><span>Vendas</span></a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link collapsed" data-bs-target="#components-product-digital" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-bag-plus"></i><span>Produtos</span><i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <ul id="components-product-digital" class="nav-content collapse " data-bs-parent="#sidebar-cart">
                            <li>
                                <a href="{{ route('produtos') }}"><i class="bi bi-circle"></i><span>Produtos</span></a>
                            </li>
                            <li>
                                <a href="{{ route('produtos-vendas') }}"><i class="bi bi-circle"></i><span>Vendas</span></a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link collapsed" data-bs-target="#components-mkt" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-lightbulb"></i><span>Marketing digital</span><i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <ul id="components-mkt" class="nav-content collapse " data-bs-parent="#sidebar-cart">
                            <li>
                                <a href="{{ route('banners') }}"><i class="bi bi-circle"></i><span>Banners</span></a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="{{ route('usuarios') }}"><i class="bi bi-person"></i><span>Usuários</span></a>
                    </li>
                @endif
            </ul>
        </aside>

        <main id="main" class="main">
            <div class="pagetitle">
                <h1>@yield('title')</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('app') }}">Página Inicial</a></li>
                        <li class="breadcrumb-item active">@yield('title')</li>
                    </ol>
                </nav>
            </div>

            <section class="section dashboard">
                <div class="row">
                    @yield('content')
                </div>
            </section>
        </main>

        <footer id="footer" class="footer">
            <div class="copyright">
                &copy; Copyright <strong><span>{{ env('APP_NAME') }}</span></strong>. Todos os direitos reservados
            </div>
            <div class="credits">
                Desenvolvido por <a href="https://expressoftwareclub.com/">Express Software Club</a>
            </div>
        </footer>

        <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

        <script src="{{ asset('template/vendor/apexcharts/apexcharts.min.js') }}"></script>
        <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('template/vendor/chart.js/chart.umd.js') }}"></script>
        <script src="{{ asset('template/vendor/echarts/echarts.min.js') }}"></script>
        <script src="{{ asset('template/vendor/tinymce/tinymce.min.js') }}"></script>
        <script src="{{ asset('template/js/main.js') }}"></script>
        <script src="{{ asset('template/js/jquery.js') }}"></script>
        <script src="{{ asset('template/js/sweetalert.js') }}"></script>
        <script>
            @if(session('error'))
                Swal.fire({
                    title: 'Erro!',
                    text: '{{ session('error') }}',
                    icon: 'error',
                    timer: 3000
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
                    timer: 3000
                })
            @endif

            document.addEventListener('DOMContentLoaded', function () {

                const deleteForms = document.querySelectorAll('form.delete');
                deleteForms.forEach(form => {
                    form.addEventListener('submit', function (event) {
                        
                        event.preventDefault();
                        Swal.fire({
                            title: 'Tem certeza?',
                            text: 'Você realmente deseja excluir este registro?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Sim',
                            confirmButtonColor: '#008000',
                            cancelButtonText: 'Não',
                            cancelButtonColor: '#FF0000',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                });
            });
        </script>
    </body>
</html>