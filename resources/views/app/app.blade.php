@extends('app.layout')
@section('title') Dashboard @endsection
@section('content')

    <div class="col-sm-12 col-md-7 col-lg-7 mb-3">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-3 col-lg-3 text-center d-flex align-items-center justify-content-center">
                            <img src="{{ asset('template/img/components/monitoring.png') }}" class="img-fluid w-50" alt="Trabalhando...">
                        </div>
                        <div class="col-12 col-sm-12 col-md-9 col-lg-9">
                            <div class="card-body">
                                <h5 class="card-title">Olá, {{ Auth::user()->name }}!</h5>
                                <p class="card-text">
                                    O seu plano atual é: <a href="{{ route('planos') }}">{{ Auth::user()->labelPlan->name }}</a> <br>
                                    Aproveite os benefícios da sua assinatura.
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
                                <div class="d-flex align-items-center">
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
                                <div class="d-flex align-items-center">
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
                                <h5 class="card-title text-center">Progresso</h5>
                                <div class="d-flex align-items-center">
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