@extends('app.layout')
@section('title') Cadernos @endsection
@section('content')

    <div class="col-sm-12 col-md-12 col-lg-12 card mb-3 p-5">
        <div class="row g-0">

            <div class="col-12">
                <div class="btn-group" role="group">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#newPlan" class="btn btn-dark modal-swal">Novo Caderno</button>
                    <button type="button" title="Excel" class="btn btn-outline-dark"><i class="bi bi-file-earmark-excel"></i></button>
                    <a href="{{ route('usuarios') }}" title="Recarregar" class="btn btn-outline-dark"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>

                <div class="modal fade" id="newPlan" tabindex="-1" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Detalhes:</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('create-notebook') }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-8 col-lg-8">
                                            <div class="form-floating mb-2">
                                                <input type="text" name="name" class="form-control" id="name" placeholder="Nome:" required>
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
                                                            <option value="{{ $subject->id }}" id-quanty="{{ $subject->countQuestions() }}">{{ $subject->name }}</option>
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
                                                            <option value="{{ $topic->id }}" id-quanty="{{ $topic->countQuestions() }}">{{ $topic->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-4 col-sm-4 col-md-4">
                                                    <button type="button" id="select-all-topics" title="Selecionar todos os tópicos" class="btn btn-block btn-dark"><i class="bi bi-ui-checks"></i> Todos</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-2">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="remove_question_resolved" id="flexSwitchCheckChecked">
                                                <label class="form-check-label" for="flexSwitchCheckChecked">Eliminar questão já resolvidas</label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-2">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="show_question_fail" id="flexSwitchCheckChecked">
                                                <label class="form-check-label" for="flexSwitchCheckChecked">Mostrar apenas as que eu já errei</label>
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
            </div>

            <div class="col-12 col-sm-12 col-md-12 col-lg-12 mt-3">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nome</th>
                                <th scope="col">Conteúdos</th>
                                <th scope="col" class="text-center">Progresso</th>
                                <th scope="col" class="text-center">Questões</th>
                                <th scope="col" class="text-center">Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($notebooks as $notebook)
                                <tr>
                                    <th scope="row">{{ $notebook->id }}</th>
                                    <td>{{ $notebook->name }}</td>
                                    <td>
                                        @foreach ($notebook->getSubjectsNames() as $subject)
                                            <span class="badge bg-dark">{{ $subject }}</span>
                                        @endforeach

                                        @foreach ($notebook->getTopicsNames() as $topic)
                                            <span class="badge bg-secondary">{{ $topic }}</span>
                                        @endforeach
                                    </td>
                                    <td class="text-center">{{ $notebook->percentage }}%</td>
                                    <td class="text-center">{{ $notebook->countQuestions() }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('delete-notebook') }}" method="POST" class="btn-group delete" role="group">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $notebook->id }}">
                                            <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                            <a href="{{ route('caderno', ['id' => $notebook->id]) }}" class="btn btn-outline-success"><i class="bi bi-arrow-bar-right"></i></a>
                                        </form>
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
        $('.modal-swal').click(function(){
            var subject = new TomSelect("#swal-subject",{
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                maxItems: 1000,
                onChange: updateQuestionCount
            });

            var topic = new TomSelect("#swal-topic",{
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

                var totalSubjectQuestions = selectedSubjects.reduce(function(total, optionId) {
                    var option = document.querySelector('#swal-subject option[value="' + optionId + '"]');
                    var quanty = parseInt(option.getAttribute('id-quanty')) || 0;
                    return total + quanty;
                }, 0);

                var totalTopicQuestions = selectedTopics.reduce(function(total, optionId) {
                    var option = document.querySelector('#swal-topic option[value="' + optionId + '"]');
                    var quanty = parseInt(option.getAttribute('id-quanty')) || 0;
                    return total + quanty;
                }, 0);

                var totalQuestions = totalSubjectQuestions + totalTopicQuestions;
                document.getElementById('question-count').textContent = `Foram encontradas: ${totalQuestions} questões`;
            }

            document.getElementById('select-all-subjects').addEventListener('click', function () {
                var allOptions = Array.from(document.querySelectorAll('#swal-subject option')).map(option => option.value);
                subject.setValue(allOptions);
            });

            document.getElementById('select-all-topics').addEventListener('click', function () {
                var allOptions = Array.from(document.querySelectorAll('#swal-topic option')).map(option => option.value);
                topic.setValue(allOptions);
            });

            updateQuestionCount();
        });
    </script>

@endsection