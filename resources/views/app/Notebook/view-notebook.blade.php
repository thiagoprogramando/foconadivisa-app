@extends('app.layout')
@section('title') Caderno: {{ $notebook->name }} @endsection
@section('content')

    <div class="col-sm-12 col-md-12 col-lg-12 card mb-3 p-5">
        <h3>{{ $notebook->name }}</h3>
        <hr>
        
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Geral</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false" tabindex="-1">Gráficos</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false" tabindex="-1">Dados</button>
            </li>
        </ul>

        <div class="tab-content pt-2" id="myTabContent">
            <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">

                @if($notebook->status == 1) 
                    <a id="result" class="btn btn-outline-success mt-3 mb-3 w-25">RESULTADO</a>
                @else
                    <a href="{{ route('answer', ['id' => $notebook->id]) }}" class="btn btn-outline-success mt-3 mb-3 w-25">COMEÇAR</a>
                @endif

                <h5 class="card-title">Analise o seu progresso</h5>
                <table class="table table-striped table-sm table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Questão</th>
                            <th scope="col">Resposta</th>
                            <th scope="col" class="text-center">Gabarito</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($answers as $key => $answer)
                        <tr>
                            <td class="w-50">{{ $key + 1 }}) {{ $answer->question->question_text }}</td>
                            <td><span class="badge bg-dark">{{ $answer->option->option_text }}</span></td>
                            <td class="text-center">
                                @if ($answer->isCorrect() == 1)
                                    <i class="bi bi-check2-circle text-success"></i>
                                @else
                                    <i class="bi bi-x-circle text-danger"></i>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <h5 class="card-title text-center">PROGRESSO GERAL</h5>
                        <canvas id="weProgressoChart" style="max-height: 200px;"></canvas>
                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                new Chart(document.querySelector('#weProgressoChart'), {
                                    type: 'doughnut',
                                    data: {
                                        labels: [
                                            'Erros',
                                            'Acertos'
                                        ],
                                        datasets: [{
                                            label: 'Avanço de respostas',
                                            data: [{{ $overallProgress['incorrect'] }}, {{ $overallProgress['correct'] }}],
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

                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <h5 class="card-title text-center">SEU PROGRESSO</h5>
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

                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <h5 class="card-title">EVOLUÇÃO</h5>
                        <p>Você está em {{ $notebook->percentage }}% de evolução, com um total de {{ $notebook->getAnsweredQuestionsCount() }} questões respondidas e {{ $notebook->getPendingQuestionsCount() }} pendentes.</p>
                        <p>Avaliação automática: {!! $notebook->getPerformanceEvaluation() !!} com  <b class="text-success">{{ $notebook->getCorrectAnswersCount() }}</b> respondidas corretamente e <b class="text-danger">{{ $notebook->getIncorrectAnswersCount() }}</b> erradas.</p>
                        <p>Conteúdos com melhor desempenho: {!! $bestPerformanceSubjects !!}</p>
                        <p>Conteúdos com pior desempenho: {!! $worstPerformanceSubjects !!}</p>

                        <p>Tópicos com melhor desempenho: {!! $bestPerformanceTopics !!}</p>
                        <p>Tópicos com pior desempenho: {!! $worstPerformanceTopics !!}</p>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <form action="{{ route('update-subject') }}" method="POST" class="row">
                    @csrf
                    <input type="hidden" name="id" value="{{ $notebook->id }}">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-floating mb-2">
                            <input type="text" name="name" class="form-control" id="name" placeholder="Nome:" value="{{ $notebook->name }}">
                            <label for="name">Nome</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-3 offset-md-9 col-lg-3 offset-lg-9">
                        <button type="submit" class="btn btn-outline-success w-100">Atualizar Caderno</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $('#result').click(function (){
            $('#contact-tab').click();
        });
    </script>
@endsection