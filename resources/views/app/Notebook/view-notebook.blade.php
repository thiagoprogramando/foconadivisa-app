@extends('app.layout')
@section('title') Caderno: {{ $notebook->name }} @endsection
@section('content')

    <div class="col-sm-12 col-md-12 col-lg-12 card mb-3 p-5">        
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Geral</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false" tabindex="-1">Gráficos</button>
            </li>
        </ul>

        <div class="tab-content pt-2" id="myTabContent">
            <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-lg-4">
                        @if($notebook->status == 1)
                            <a id="result" class="btn btn-outline-success mt-3 mb-3 w-100">RESULTADO</a>
                        @else
                            @if($notebook->getAnsweredQuestionsCount() >= 1) 
                                <a href="{{ route('answer', ['id' => $notebook->id]) }}" class="btn btn-dark mt-3 w-100">RETOMAR CADERNO</a>
                            @else
                                <a href="{{ route('answer', ['id' => $notebook->id]) }}" class="btn btn-dark mt-3 w-100">COMEÇAR</a>
                            @endif
                        @endif

                        <a href="{{ route('caderno-filtros', ['id' => $notebook->id]) }}" class="btn btn-outline-dark mt-1 w-100"><i class="bx bx-filter"></i> FILTROS</a>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <div class="row">
                    <div class="col-sm-12 col-md-8 col-lg-8">
                        <h5 class="card-title">EVOLUÇÃO</h5>
                        <p>Você está em {{ $notebook->percentage }}% de evolução, com um total de {{ $notebook->getAnsweredQuestionsCount() }} questões respondidas e {{ $notebook->getPendingQuestionsCount() }} pendentes.</p>
                        <p>Avaliação automática: {!! $notebook->getPerformanceEvaluation() !!} com  <b class="text-success">{{ $notebook->getCorrectAnswersCount() }}</b> acertos e <b class="text-danger">{{ $notebook->getIncorrectAnswersCount() }}</b> erros.</p>
                        <p>Conteúdos com melhor desempenho: {!! $bestPerformanceSubjects !!}</p>
                        <p>Conteúdos com pior desempenho: {!! $worstPerformanceSubjects !!}</p>

                        <p>Tópicos com melhor desempenho: {!! $bestPerformanceTopics !!}</p>
                        <p>Tópicos com pior desempenho: {!! $worstPerformanceTopics !!}</p>
                    </div>

                    <div class="col-sm-12 col-md-4 col-lg-4">
                        <h5 class="card-title text-center">DESEMPENHO</h5>
                        <canvas id="youProgressChart" style="max-height: 200px;"></canvas>
                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                new Chart(document.querySelector('#youProgressChart'), {
                                    type: 'doughnut',
                                    data: {
                                        labels: [
                                            'Erros',
                                            'Acertos'
                                        ],
                                        datasets: [{
                                            label: 'Avanço de respostas',
                                            data: [{{ $notebook->getIncorrectAnswersCount() }}, {{ $notebook->getCorrectAnswersCount() }}],
                                            backgroundColor: [
                                            'rgb(255, 99, 132)',
                                            'rgb(153, 204, 50)'
                                            ],
                                            hoverOffset: 4
                                        }]
                                    }
                                });
                            });
                        </script> 
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('#result').click(function () {
                $('#contact-tab').click();
            });
    
            @if (!empty($tab))
                $('#{!! $tab !!}').click();
            @endif
        });
    </script>    
@endsection