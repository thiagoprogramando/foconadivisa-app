@extends('app.layout')
@section('title') Caderno: {{ $notebook->name }} @endsection
@section('content')

    <div class="col-sm-12 col-md-12 col-lg-12 card mb-3 p-5">        
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Filtros</button>
            </li>
            {{-- <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false" tabindex="-1">Gráficos</button>
            </li> --}}
        </ul>

        <div class="tab-content pt-2" id="myTabContent">
            <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
                <form action="{{ route('update-notebook') }}" method="POST" class="row">
                    @csrf
                    <input type="hidden" name="id" value="{{ $notebook->id }}">
                    <div class="col-12 col-sm-12 col-md-8 col-lg-8">
                        <div class="form-floating mb-2">
                            <input type="text" name="name" class="form-control" id="name" placeholder="Nomeie seu caderno:" value="{{ $notebook->name }}">
                            <label for="name">Nomeie seu caderno</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="form-floating mb-2">
                            <input type="number" name="number" class="form-control" id="questions" placeholder="N° questões:" value="{{ $notebook->countQuestions() }}">
                            <label for="questions">N° questões</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-2">
                        <div class="row">
                            <div class="col-8 col-sm-8 col-md-8">
                                <select id="swal-subject" name="subject[]" placeholder="Escolha de conteúdos">
                                    <option value="" selected>Escolha de conteúdos</option>
                                    @foreach($subjectsFromPlan as $subject)
                                        <option value="{{ $subject->id }}" data-topics='@json($subject->topics)' id-quanty="{{ $subject->totalQuestions() }}" id-resolved="{{ $subject->questionResolved() }}" id-fail="{{ $subject->questionFail() }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4 col-sm-4 col-md-4">
                                <button type="button" id="select-all-subjects" title="Selecionar todos os conteúdos" class="btn btn-block btn-dark">
                                    <i class="bi bi-ui-checks"></i> Todos
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-2">
                        <div class="row">
                            <div class="col-8 col-sm-8 col-md-8">
                                <select id="swal-topic" name="topic[]" placeholder="Escolha de tópicos" multiple>
                                    <option value="" selected>Escolha de tópicos</option>
                                    @foreach($topicsFromPlan as $topic)
                                        <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4 col-sm-4 col-md-4">
                                <button type="button" id="select-all-topics" title="Selecionar todos os tópicos" class="btn btn-block btn-dark">
                                    <i class="bi bi-ui-checks"></i> Todos
                                </button>
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
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 mt-3">
                        <small class="btn btn-dark" id="question-count">Foram encontradas: 0 questões</small>
                    </div>
                    <div class="col-12 col-sm-12 offset-md-4 col-md-4 offset-lg-4 col-lg-4 mt-3">
                        <button type="submit" class="btn btn-outline-success w-100">Atualizar caderno</button>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <div class="row">
                    
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            var topic = new TomSelect("#swal-topic", {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                plugins: ['remove_button'],
                persist: false,
                maxItems: 1000,
            });

            var selectedTopicIds = @json($selectedTopicIds);
            selectedTopicIds.forEach(function(id) {
                topic.addItem(id);
            });

            var subject = new TomSelect("#swal-subject", {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                plugins: ['remove_button'],
	            persist: false,
                maxItems: 1000,
                onChange: function() {
                    updateTopics();
                    updateQuestionCount();
                },
                onDelete: function(values) {

                    var topicSelect = document.getElementById('swal-topic');

                    values.forEach(function(value) {
                        var subjectIdToRemove = value.trim(); 
    
                        var currentOptions = Array.from(topicSelect.options);
                        currentOptions.forEach(function(option) {
                            if (option.getAttribute('data-subject') === subjectIdToRemove) {
                                option.remove(); 
                            }
                        });
                    });

                    topicSelect.dispatchEvent(new Event('change'));
                }
            });

            var selectedIds = @json($selectedSubjectIds);
            selectedIds.forEach(function(id) {
                subject.addItem(id);
            });

            function updateQuestionCount() {

                var selectedSubjects = Array.from(subject.getValue());
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

                document.getElementById('question-count').textContent = `Foram encontradas: ${totalQuestions} questões`;

                var inputQuestions = document.getElementById('questions');
                inputQuestions.max = totalQuestions;

                if (parseInt(inputQuestions.value) > totalQuestions) {
                    inputQuestions.value = totalQuestions;
                }
            }

            function updateTopics() {

                var selectedSubjects = Array.from(subject.getValue());
                var topicSelect = document.getElementById('swal-topic');
                var addedTopicIds = new Set();
                
                selectedSubjects.forEach(function(optionId) {
                    var option = document.querySelector('#swal-subject option[value="' + optionId + '"]');
                    var topics = JSON.parse(option.getAttribute('data-topics'));

                    topics.forEach(function(topic) {
                        if (!addedTopicIds.has(topic.id)) {
                            var newOption = document.createElement('option');
                            newOption.value = topic.id;
                            newOption.setAttribute('data-subject', topic.subject_id);
                            newOption.innerHTML = topic.name;
                            topicSelect.appendChild(newOption);
                            addedTopicIds.add(topic.id);
                        }
                    });
                });

                if (selectedSubjects.length === 0) {
                    topicSelect.innerHTML = '<option value="" selected>Escolha de tópicos (opcional)</option>';
                }

                topic.sync();
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

            $('input[type="radio"]').click(function() {
                var $radio = $(this);

                if ($radio.data('wasChecked') === true) {
                    $radio.prop('checked', false);
                    $radio.data('wasChecked', false);
                } else {
                    $('input[type="radio"]').data('wasChecked', false);
                    $radio.data('wasChecked', true);
                }

                updateQuestionCount();
            });
        });
    </script>
@endsection