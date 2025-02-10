@extends('app.layout')
@section('title') Caderno: {{ $notebook->name }} @endsection
@section('content')

    <style>
        .form-check-question {
            font-size: 18px !important;
        }

        .form-check-label {
            font-size: 18px !important;
        }

        .question {
            font-size: 18px !important;
        }

        .custom-radio {
            position: relative;
            margin-right: 20px;
        }

        .custom-radio::before {
            content: attr(data-letter);
            position: absolute;
            top: 50%;
            left: 0;
            transform: translate(-50%, -50%);
            background-color: white;
            border: 1px solid #000;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 12px;
            color: black;
            z-index: 1;
        }

        .option-letter {
            margin-right: 8px;
            font-weight: bold;
        }

        .option-container {
            display: flex;
            align-items: center;
        }

        .custom-radio {
            position: relative;
            margin-right: 10px;
            flex-shrink: 0;
        }

        .custom-radio::before {
            content: attr(data-letter);
            position: absolute;
            top: 50%;
            left: 0;
            transform: translate(-50%, -50%);
            background-color: white;
            border: 1px solid #000;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 12px;
            color: black;
            z-index: 1;
        }

        .form-check-label {
            flex: 1;
            word-wrap: break-word;
            margin-left: 10px;
        }

        #comments {
            border: 1px solid #000;
            border-radius: 5px;
        }

        #resolution {
            border: 1px solid #000;
            border-radius: 5px;
        }
    </style>

    <div class="col-sm-12 col-md-12 col-lg-12 card mb-3 p-3">
        @if($unansweredQuestions && $unansweredQuestions->count() > 0)
            @foreach($unansweredQuestions as $index => $notebookQuestion)
                @php
                    $question = $notebookQuestion->question;
                    $letters = ['A', 'B', 'C', 'D', 'E'];
                @endphp

                @if($question)
                    <div class="card-header">
                        <div class="row mb-3">
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <h6 class="question">
                                    Questão: {{ $nextQuestionNumber }} de {{ $totalQuestions }}
                                </h6>
                                @if($question->subject || $question->topic)
                                    <small><b>Conteúdo/Tópico:</b> 
                                        {{ $question->subject->parent->name ?? $question->subject->name }} |
                                        {{ $question->topic->name ?? '---' }}
                                    </small><br>
                                @endif
                                @if($question->jury)
                                    <small><b>Banca:</b> 
                                        {{ $question->jury->name ?? '---' }}
                                    </small><br>
                                @endif
                                <small><b>{{ $question->responsesCount(Auth::user()->id, $notebook->id) }}</b> Resolvidas</small> <small class="text-success"><b>{{ $question->correctCount(Auth::user()->id, $notebook->id) }}</b> Acertos</small> <small class="text-danger"><b>{{ $question->wrogCount(Auth::user()->id, $notebook->id) }}</b> Erros</small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="btn-group">
                                    <a href="{{ route('ver-questao', ['id' => $question->id]) }}" target="_blank" class="btn btn-outline-dark" title="Dados da Questão"><i class="bi bi-pie-chart"></i> Dados</a>
                                    <a href="#" class="btn btn-outline-dark" id="updateNotebook" title="Modificar filtros">
                                        <i class="bx bx-filter"></i> Filtros
                                    </a>
                                    <button class="btn btn-outline-dark" title="Relatar problema" data-bs-toggle="modal" data-bs-target="#newTicket"><i class="ri-alarm-warning-fill"></i> Relatar problema</button>
                                    <button type="button" class="btn btn-outline-dark btn-resolution" title="Comentário do Professor"><i class="bx bxs-book-reader"></i></button>
                                    <button type="button" class="btn btn-outline-dark btn-comment" title="Comentários sobre a questão"><i class="bi bi-chat-square-text"></i></button>
                                    <button type="button" class="btn btn-outline-dark" onclick="favorite({{ $question->id }})" title="Favoritar"><i id="favorite" class="bi {{ $question->favorites->where('user_id', Auth::id())->isNotEmpty() ? 'bi-heart-fill' : 'bi-heart' }} text-danger"></i></button>
                                </div>

                                <div class="modal fade" id="newTicket" tabindex="-1" aria-hidden="true" style="display: none;">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Detalhes:</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form id="ticketForm">
                                                @csrf
                                                <input type="hidden" name="question_id" value="{{ $question->id }}">
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                                            <textarea name="comment" class="form-control" rows="4" placeholder="Descreva o problema:"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer btn-group">
                                                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Fechar</button>
                                                    <button type="submit" class="btn btn-dark">Enviar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">

                        <div id="resolution" class="d-none p-3 mt-3 mb-3">
                            <p class="lead">Resolução - <small style="font-size: 14px;">Data do comentário: {{ $question->updated_at->format('d/m/Y') }}</small></p>
                            {!! $question->comment_text !!}
                        </div>

                        <div id="comments" class="d-none p-3 mt-3">
                            <p class="lead">Comentários</p>

                            <form action="{{ route('create-comment') }}" method="POST" class="d-flex btn-group w-50 mb-3">
                                @csrf
                                <input type="hidden" name="question_id" value="{{ $question->id }}">
                                <input type="text" name="comment" class="form-control" placeholder="Faça seu comentário..." required>
                                <button class="btn btn-dark"><i class="bi bi-plus-circle"></i></button>
                            </form>

                            @foreach ($question->comments as $comment)
                                <div class="alert alert-light border-light alert-dismissible fade show" role="alert">
                                    {{ $comment->user->name }} - <small>{{ $comment->created_at->format('d/m/Y') }}</small> <br> {{ $comment->comment }}
                                    <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="alert" aria-label="Close"></button>
                                  </div>
                            @endforeach
                        </div>

                        <h6 class="card-title p-2 mt-2 mb-3 bg-light"> 
                            <a href="">#{{ $question->id }}</a> {!! $question->question_text !!} 
                        </h6>
                        <form id="questionForm" method="POST" action="{{ route('submitAnswerAndNext', [$notebook->id, $notebookQuestion->id, $unansweredQuestions->currentPage()]) }}">
                            @csrf
                            <input type="hidden" name="notebook_question_id" value="{{ $notebookQuestion->id }}">
                            <div class="bg-light p-3">
                                @foreach($question->options as $index => $option)
                                    <div class="form-check-questio form-question option-container mb-3 p-2 w-100">
                                        <input class="form-check-input" type="radio" name="option_id" value="{{ $option->id }}" id="option{{ $option->id }}" onclick="selectOption(this)" ondblclick="toggleStrikethroughOption(this)">
                                        <label class="form-check-label" for="option{{ $option->id }}">
                                            <button type="button" class="btn btn-link" onclick="toggleStrikethroughOption(this)">
                                                <i class="bi bi-scissors text-danger ms-2" title="Eliminar alternativa" style="cursor: pointer;"></i>
                                            </button>
                                            <span class="option-letter" ondblclick="toggleStrikethroughOption(this)">{{ $letters[$index] }})</span> 
                                            {{ $option->option_text }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <hr class="mt-5">
                            <div class="text-center">
                                <div class="btn-group mt-3" role="group" style="width: 70%;">
                                    <button onclick="history.back()" @disabled($nextQuestionNumber == 1) title="Voltar" class="btn btn-outline-dark">VOLTAR</button>
                                    <a href="{{ route('delete-question-answer', ['notebook' => $notebook->id, 'question' => $question->id]) }}" title="Eliminar Questão" class="btn btn-outline-danger">ELIMINAR QUESTÃO</a>
                                    <button type="submit" title="Responder" class="btn btn-outline-success">RESPONDER</button>
                                    @if ($unansweredQuestions->count() > 0)
                                        {{ $unansweredQuestions->links() }}
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                @else
                    <p>Questão não encontrada.</p>
                @endif
            @endforeach
        @else
            <div class="text-center">
                <i class="bi bi-award text-success" style="font-size: 86px;"></i>
                <h6 class="card-title">Parabéns! <br> Você completou o caderno.</h6>
                <a href="{{ route('completing-notebook', ['id' => $notebook->id]) }}" class="btn btn-outline-success">Ver resultado</a>
            </div>
        @endif
    </div>

    <script>
        document.getElementById('questionForm').addEventListener('submit', function(event) {
    
            event.preventDefault();
            const isOptionSelected = document.querySelector('input[name="option_id"]:checked');
            if (!isOptionSelected) {
                Swal.fire({
                    icon: 'info',
                    title: 'Selecione uma opção',
                    text: 'Por favor, escolha uma opção antes de enviar sua resposta.',
                    confirmButtonText: 'OK'
                });
            } else {
                this.submit();
            }
        });

        function selectOption(selected) {

            const optionContainers = document.querySelectorAll('.option-container');
            optionContainers.forEach(container => {
                container.classList.remove('selected-option');
            });

            const selectedContainer = selected.parentElement;
            selectedContainer.classList.add('selected-option');
        }

        function toggleStrikethroughOption(element) {

            const label = element.closest('label');
            if (label) {

                const isStrikethrough = label.style.textDecoration === 'line-through';
                label.style.textDecoration = isStrikethrough ? 'none' : 'line-through';

                const span = label.querySelector('.option-letter');
                if (span) {
                    span.style.textDecoration = isStrikethrough ? 'none' : 'line-through';
                }
            }
        }

        document.querySelectorAll('.form-check-label').forEach((label) => {
            label.addEventListener('dblclick', function() {
                toggleStrikethroughOption(this);
            });
        });

        $('.btn-comment').on('click', function() {
            $('#comments').toggleClass('d-none');

            $('html, body').animate({
                scrollTop: $('#comments').offset().top - 100
            }, 400);
        });

        $('.btn-resolution').on('click', function() {
            $('#resolution').toggleClass('d-none');

            $('html, body').animate({
                scrollTop: $('#resolution').offset().top - 100
            }, 400);
        });

        document.getElementById('updateNotebook').addEventListener('click', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Deseja atualizar caderno?',
                text: 'Você perderá todas as questões resolvidas até o momento.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'SIM',
                cancelButtonText: 'NÃO'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('caderno-filtros', ['id' => $notebook->id]) }}";
                }
            });
        });

        document.getElementById('ticketForm').addEventListener('submit', function(event) {
            event.preventDefault();

            let form = event.target;
            let formData = new FormData(form);

            formData.append('user_id', "{{ Auth::user()->id }}");

            fetch("{{ route('create-ticket') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title : 'Sucesso!',
                        text  : 'Seu ticket foi enviado com sucesso!',
                        icon  : 'success',
                        timer : 3000
                    });

                    form.reset();
                    let modal = bootstrap.Modal.getInstance(document.querySelector('.modal'));
                    modal.hide();
                } else {
                    Swal.fire({
                        title : 'Erro!',
                        text  : data.message || 'Houve um problema ao enviar o ticket.',
                        icon  : 'error',
                        timer : 3000
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title : 'Erro!',
                    text  : 'Erro ao processar a requisição!',
                    icon  : 'error',
                    timer : 3000
                });
            });
        });

        function favorite(question_id) {
            
            fetch("{{ route('create-favorite') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: new URLSearchParams({
                    question_id : question_id,
                    user_id     : "{{ Auth::user()->id }}"
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title : 'Sucesso!',
                        text  : data.message,
                        icon  : 'success',
                        timer : 3000
                    });

                    $('#favorite').removeClass().addClass(data.icon);
                } else {
                    Swal.fire({
                        title : 'Erro!',
                        text  : data.message || 'Houve um problema, tente novamente mais tarde!',
                        icon  : 'error',
                        timer : 3000
                    });

                    $('#favorite').removeClass().addClass(data.icon);
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Erro!',
                    text: 'Erro ao processar a requisição!',
                    icon: 'error',
                    timer: 3000
                });
            });
        }
    </script>
@endsection