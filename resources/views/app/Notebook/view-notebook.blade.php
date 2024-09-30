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
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false" tabindex="-1">Dados</button>
            </li>
        </ul>

        <div class="tab-content pt-2" id="myTabContent">
            <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">

                @if($notebook->status == 1) 
                    <a id="result" class="btn btn-outline-success mt-3 mb-3">RESULTADO</a>
                @else
                    <a href="{{ route('answer', ['id' => $notebook->id]) }}" class="btn btn-dark mt-3 mb-3">COMEÇAR</a>
                @endif
            </div>

            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <h5 class="card-title text-center">Desempenho (demais usuários)</h5>
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
                        <h5 class="card-title text-center">Desempenho (seu)</h5>
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
                <form class="row">
                    <input type="hidden" name="id" value="{{ $notebook->id }}">
                    <div class="col-12 col-sm-12 col-md-8 col-lg-8">
                        <div class="form-floating mb-2">
                            <input type="text" name="name" class="form-control" id="name" placeholder="Nome:" value="{{ $notebook->name }}" readonly>
                            <label for="name">Caderno</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="form-floating mb-2">
                            <input type="number" class="form-control" placeholder="Questões" value="{{ $notebook->questions->count() }}" readonly>
                            <label for="questions">Questões</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-2">
                        <select id="swal-subject-disabled" multiple placeholder="Conteúdos" disabled>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" 
                                    id-quanty="{{ $subject->countQuestions() }}" id-resolved="{{ $subject->questionResolved() }}" id-fail="{{ $subject->questionFail() }}"
                                    {{ in_array($subject->id, old('subject', $selectedSubjects)) ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-2">
                        <select id="swal-topic-disabled" multiple placeholder="Tópicos" disabled>
                            @foreach($topics as $topic)
                                <option value="{{ $topic->id }}" 
                                    {{ in_array($topic->id, old('topics', $selectedTopics)) ? 'selected' : '' }}>
                                    {{ $topic->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-sm-12 offset-md-8 col-md-4 offset-lg-8 col-lg-4 btn-group">
                        <button type="button" class="btn btn-dark modal-swal" data-bs-toggle="modal" data-bs-target="#newPlan"><i class="bi bi-plus-circle"></i> Questões</button>
                        <a href="{{ route('delete-notebook-get', ['id' => $notebook->id]) }}" class="btn btn-outline-danger"><i class="bi bi-trash"></i> Excluir</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="newPlan" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalhes:</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('update-notebook') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $notebook->id }}">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-8 col-lg-8">
                                <div class="form-floating mb-2">
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Nome:" value="{{ $notebook->name }}" required>
                                    <label for="name">Nome</label>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="form-floating mb-2">
                                    <input type="number" name="number" class="form-control" id="questions" placeholder="N° questões:" required>
                                    <label for="questions">N° questões</label>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-2">
                                <div class="row">
                                    <div class="col-8 col-sm-8 col-md-8">
                                        <select id="swal-subject" name="subject[]" placeholder="Escolha de conteúdos">
                                            <option value="" selected>Escolha de conteúdos</option>
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}" 
                                                    id-quanty="{{ $subject->countQuestions() }}" id-resolved="{{ $subject->questionResolved() }}" id-fail="{{ $subject->questionFail() }}"
                                                    {{ in_array($subject->id, old('subject', $selectedSubjects)) ? 'selected' : '' }}>
                                                    {{ $subject->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-4 col-sm-4 col-md-4">
                                        <button type="button" id="select-all-subjects" title="Selecionar todos os conteúdos" class="btn btn-block btn-dark"><i class="bi bi-ui-checks"></i> Todos</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-2">
                                <div class="row">
                                    <div class="col-8 col-sm-8 col-md-8">
                                        <select id="swal-topic" name="topics[]" placeholder="Escolha de tópicos (opcional)">
                                            <option value="" selected>Escolha de tópicos (opcional)</option>
                                            @foreach($topics as $topic)
                                                <option value="{{ $topic->id }}" 
                                                    id-quanty="{{ $topic->countQuestions() }}" id-resolved="{{ $topic->questionResolved() }}" id-fail="{{ $topic->questionFail() }}"
                                                    {{ in_array($topic->id, old('topics', $selectedTopics)) ? 'selected' : '' }}>
                                                    {{ $topic->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-4 col-sm-4 col-md-4">
                                        <button type="button" id="select-all-topics" title="Selecionar todos os tópicos" class="btn btn-block btn-dark"><i class="bi bi-ui-checks"></i> Todos</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="filter" value="remove_question_resolved" id="removeQuestionResolved">
                                    <label class="form-check-label" for="removeQuestionResolved">Eliminar questão já resolvidas</label>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="filter" value="show_question_fail" id="showQuestionFail">
                                    <label class="form-check-label" for="showQuestionFail">Mostrar apenas as que eu já errei</label>
                                </div>
                            </div>                                                                                
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 mt-3">
                                <small class="btn btn-dark" id="question-count">Foram encontradas: 0 questões</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-outline-success">Criar Caderno</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        new TomSelect("#swal-subject-disabled", {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            },
            maxItems: 1000,
        });

        new TomSelect("#swal-topic-disabled", {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            },
            maxItems: 1000,
        });

        $('#result').click(function (){
            $('#contact-tab').click();
        });

        $('.modal-swal').click(function(){

            var subject = new TomSelect("#swal-subject", {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                maxItems: 1000,
                onChange: updateQuestionCount
            });

            var topic = new TomSelect("#swal-topic", {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                maxItems: 1000,
                onChange: updateQuestionCount
            });

            function updateQuestionCount() {

                var selectedSubjects = Array.from(subject.getValue());
                var selectedTopics = Array.from(topic.getValue());

                var filter = $('input[name="filter"]:checked').val();

                var totalQuestions = 0;

                selectedSubjects.forEach(function(optionId) {
                    var option = document.querySelector('#swal-subject option[value="' + optionId + '"]');
                    var quanty = parseInt(option.getAttribute('id-quanty')) || 0;
                    var resolved = parseInt(option.getAttribute('id-resolved')) || 0;
                    var fail = parseInt(option.getAttribute('id-fail')) || 0;

                    if (filter === 'remove_question_resolved') {
                        totalQuestions += quanty;
                        totalQuestions -= resolved;
                    } else if (filter === 'show_question_fail') {
                        totalQuestions += fail;
                    } else {
                        totalQuestions += quanty;
                    }
                });

                selectedTopics.forEach(function(optionId) {
                    var option = document.querySelector('#swal-topic option[value="' + optionId + '"]');
                    var quanty = parseInt(option.getAttribute('id-quanty')) || 0;
                    var resolved = parseInt(option.getAttribute('id-resolved')) || 0;
                    var fail = parseInt(option.getAttribute('id-fail')) || 0;

                    if (filter === 'remove_question_resolved') {
                        totalQuestions += quanty;
                        totalQuestions -= resolved;
                    } else if (filter === 'show_question_fail') {
                        totalQuestions += fail;
                    } else {
                        totalQuestions += quanty;
                    }
                });

                document.getElementById('question-count').textContent = `Foram encontradas: ${totalQuestions} questões`;

                var inputQuestions = document.getElementById('questions');
                inputQuestions.max = totalQuestions;

                if (parseInt(inputQuestions.value) > totalQuestions) {
                    inputQuestions.value = totalQuestions;
                }
            }

            $('#select-all-subjects').on('click', function() {
                var allOptions = Array.from(document.querySelectorAll('#swal-subject option')).map(option => option.value);
                subject.setValue(allOptions);
                updateQuestionCount();
            });

            $('#select-all-topics').on('click', function() {
                var allOptions = Array.from(document.querySelectorAll('#swal-topic option')).map(option => option.value);
                topic.setValue(allOptions);
                updateQuestionCount();
            });

            $('#questions').on('input', function() {
                var inputQuestions = document.getElementById('questions');
                var maxQuestions = parseInt(inputQuestions.max);

                if (parseInt(inputQuestions.value) > maxQuestions) {
                    inputQuestions.value = maxQuestions;
                }
            });

            $('input[name="filter"]').on('change', function() {
                updateQuestionCount();
            });

            updateQuestionCount();
        });
    </script>
@endsection