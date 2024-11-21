@extends('app.layout')
@section('title', 'Cadernos')
@section('content')

<div class="card mb-3 p-5">
    <div class="row g-0">

        <div class="col-12 mb-3">
            <div class="btn-group">
                <a href="{{ route('criar-caderno') }}" class="btn btn-dark">Novo Caderno</a>
                <a href="{{ route('cadernos') }}" title="Recarregar" class="btn btn-outline-dark">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </div>

        <!-- Tabela de Cadernos -->
        <div class="col-12 mt-3">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th>Conteúdos</th>
                            <th class="text-center">Progresso</th>
                            <th class="text-center">Questões</th>
                            <th class="text-center">Opções</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($notebooks as $notebook)
                            <tr>
                                <th><a href="{{ route('caderno', $notebook->id) }}">{{ $notebook->id }}</a></th>
                                <td><a href="{{ route('caderno', $notebook->id) }}" class="text-dark">{{ $notebook->name }}</a></td>
                                <td>
                                    @foreach ($notebook->getSubjectsNames() as $subject)
                                        <span class="badge bg-dark">{{ $subject }}</span>
                                    @endforeach
                                    @foreach ($notebook->getTopicsNames() as $topic)
                                        <span class="badge bg-secondary">{{ $topic }}</span>
                                    @endforeach
                                </td>
                                <td class="text-center">
                                    {{ $notebook->countQuestions() > 0 ? number_format(($notebook->countQuestionsNotebook() / $notebook->countQuestions()) * 100, 2) . '%' : '0%' }}
                                </td>
                                <td class="text-center">{{ $notebook->countQuestions() }}</td>
                                <td class="text-center">
                                    <form action="{{ route('delete-notebook') }}" method="POST" class="d-inline-block">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $notebook->id }}">
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('caderno', $notebook->id) }}" class="btn btn-outline-success">
                                        <i class="bi bi-arrow-bar-right"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>   
            </div>
        </div>

    </div>
</div>

<script>
    $('.modal-swal').click(function() {

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
            const selectedSubjects = Array.from(subject.getValue());
            const topicSelect = document.getElementById('swal-topic');
            const addedTopicIds = new Set();

            // Limpa as opções existentes no select de tópicos
            topicSelect.innerHTML = '<option value="" selected>Escolha de tópicos (opcional)</option>';

            selectedSubjects.forEach(function(optionId) {
                // Obtém o elemento <option> correspondente ao ID selecionado
                const option = document.querySelector(`#swal-subject option[value="${optionId}"]`);
                if (!option) return; // Garante que a opção exista

                // Extrai os tópicos associados ao subject selecionado
                const topics = JSON.parse(option.getAttribute('data-topics') || '[]');

                // Adiciona cada tópico ao select, incluindo a contagem de questões
                topics.forEach(function(topic) {
                    if (!addedTopicIds.has(topic.id)) {
                        const newOption = document.createElement('option');
                        newOption.value = topic.id;
                        newOption.textContent = `${topic.name} (${topic.question_count || 0} questões)`;
                        newOption.setAttribute('data-subject', topic.subject_id);

                        // Adiciona a nova opção ao select de tópicos
                        topicSelect.add(newOption);
                        addedTopicIds.add(topic.id);
                    }
                });
            });

            // Sincroniza a interface com as opções atualizadas
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
            const inputQuestions = document.getElementById('questions');
            if (parseInt(inputQuestions.value) > parseInt(inputQuestions.max)) {
                inputQuestions.value = inputQuestions.max;
            }
        });

    });
</script>
@endsection
