@extends('app.layout')
@section('title') Dashboard @endsection
@section('content')

    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-3">
            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    @foreach ($banners as $index => $banner)
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $index }}"  @if($index == 0) class="active" aria-current="true" @endif></button>
                    @endforeach
                </div>

                <div class="carousel-inner">
                    @foreach ($banners as $index => $banner)
                        <div class="carousel-item @if($index == 0) active @endif">
                            <img src="{{ asset('storage/'.$banner->file) }}" class="d-block w-100" alt="{{ $banner->name }}">
                        </div>
                    @endforeach
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </div>

    <div class="col-sm-12 col-md-7 col-lg-7 mb-3">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-3 col-lg-3 text-center d-flex align-items-center justify-content-center">
                            <img src="{{ asset('template/img/components/monitoring.png') }}" class="img-fluid" alt="Trabalhando...">
                        </div>
                        <div class="col-12 col-sm-12 col-md-9 col-lg-9">
                            <div class="card-body">
                                <h5 class="card-title">Olá, {{ Auth::user()->name }}!</h5>
                                <p class="card-text">
                                    @if (Auth::user()->labelPlan)
                                        O seu plano atual é: <a href="{{ route('planos') }}">{{ Auth::user()->labelPlan->name }}</a> <br>
                                        {!! Auth::user()->validadMonth() !!}
                                    @else
                                        Você ainda não escolheu um <a href="{{ route('planos') }}"><b>Plano</b></a>, faça a melhor escolha e aproveite os benefícios!
                                    @endif
                                </p>                                                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="col-6 col-sm-6 col-md-4 col-lg-4">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title text-center">Questões <span>| Hoje</span></h5>
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"> <i class="bi bi-question-square-fill"></i> </div>
                                    <div class="ps-3">
                                        <h6>{{ $questionsTodayCount }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
        
                    <div class="col-6 col-sm-6 col-md-4 col-lg-4">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title text-center">Questões <span>| Geral</span></h5>
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"> <i class="bi bi-book-half"></i> </div>
                                    <div class="ps-3">
                                        <h6>{{ $totalQuestionsCount }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
        
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title text-center">Progresso <span>| Meta</span></h5>
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"> <i class="bi bi-bar-chart-fill"></i> </div>
                                    <div class="ps-3">
                                        <h6>{{ number_format($progress, 2) }}%</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12 col-md-5 col-lg-5">
        @if($errorsCount > 0 || $correctCount > 0)
            <div class="card">
                <div class="card-body">
                <h5 class="card-title text-center">GRÁFICO DE RESPOSTAS</h5>
                <canvas id="doughnutChart" style="max-height: 200px;"></canvas>
                    <script>
                        document.addEventListener("DOMContentLoaded", () => {

                            const errorsCount = @json($errorsCount);

                            const correctCount = @json($correctCount);

                            new Chart(document.querySelector('#doughnutChart'), {
                                type: 'doughnut',
                                data: {
                                    labels: [
                                        'Erros',
                                        'Acertos'
                                    ],
                                    datasets: [{
                                        label: 'Avanço de respostas',
                                        data: [errorsCount, correctCount],
                                        backgroundColor: [
                                        '#FF0000',
                                        '#00CC00'
                                        ],
                                        hoverOffset: 4
                                    }]
                                }
                            });
                        });
                    </script>
                </div>
            </div>
        @endif
    </div>
@endsection