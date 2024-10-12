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
                            <div class="col-12 col-sm-12 col-md-8 col-lg-8">
                                <h6 class="question">
                                    Questão: {{ $nextQuestionNumber }} de {{ $totalQuestions }}
                                </h6>
                                <small><b>Conteúdo/Tópico:</b> {{ $question->subject->name }}</small> <br>
                                <small><b>{{ $question->responsesCount(Auth::user()->id, $notebook->id) }}</b> Resolvidas</small> <small class="text-success"><b>{{ $question->correctCount(Auth::user()->id, $notebook->id) }}</b> Acertos</small> <small class="text-danger"><b>{{ $question->wrogCount(Auth::user()->id, $notebook->id) }}</b> Erros</small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="btn-group">
                                    <a class="btn btn-outline-dark" title="Dados da Questão"><i class="bi bi-pie-chart"></i> Dados</a>
                                    <a href="{{ route('caderno-filtros', ['id' => $notebook->id]) }}" class="btn btn-outline-dark" title="Modificar filtros"><i class="bx bx-filter"></i> Filtros</a>
                                    <button type="button" class="btn btn-outline-dark btn-resolution" title="Comentário do Professor"><i class="bx bxs-book-reader"></i></button>
                                    <button type="button" class="btn btn-outline-dark btn-comment" title="Comentários sobre a questão"><i class="bi bi-chat-square-text"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">

                        <div id="resolution" class="d-none p-3 mt-3 mb-3">
                            <p class="lead">Resolução - <small>{{ $question->updated_at->format('d/m/Y') }}</p>
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
                                <div class="alert alert-dark bg-dark text-light border-0 alert-dismissible fade show" role="alert">
                                    {{ $comment->user->name }} - <small>{{ $comment->created_at->format('d/m/Y') }}</small> <br> {{ $comment->comment }}
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                                  </div>
                            @endforeach
                        </div>

                        <h6 class="card-title p-2 mt-2 mb-3 bg-light"> <a href="">#{{ $question->id }}</a> {!! $question->question_text !!} </h6>
                        <form id="questionForm" method="POST" action="{{ route('submitAnswerAndNext', [$notebook->id, $notebookQuestion->id, $unansweredQuestions->currentPage()]) }}">
                            @csrf
                            
                            <input type="hidden" name="notebook_question_id" value="{{ $notebookQuestion->id }}">

                            <div class="bg-light p-3">
                                @foreach($question->options as $index => $option)
                                    <div class="form-check-questio form-question option-container mb-3 p-2 w-100">
                                        <input class="form-check-input" type="radio" name="option_id" value="{{ $option->id }}" id="option{{ $option->id }}" onclick="selectOption(this)" ondblclick="toggleStrikethroughOption(this)">
                                        <label class="form-check-label" for="option{{ $option->id }}">
                                            <span class="option-letter" ondblclick="toggleStrikethroughOption(this)">{{ $letters[$index] }})</span> {{ $option->option_text }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <hr class="mt-5">
                            <div class="text-center">
                                <div class="btn-group mt-3" role="group" style="width: 70%;">
                                    <a href="{{ route('caderno', ['id' => $notebook->id]) }}" class="btn btn-dark">SAIR</a>
                                    <a href="{{ route('delete-question-answer', ['notebook' => $notebook->id, 'question' => $question->id]) }}" title="Eliminar Questão" class="btn btn-outline-danger"><i class="bi bi-trash"></i></a>
                                    <button type="submit" class="btn btn-outline-success">RESPONDER</button>
                                </div>
                            </div>
                        </form>
                    </div>
                @else
                    <p>Questão não encontrada.</p>
                @endif
            @endforeach

            <div class="mt-3 text-center">
                {{ $unansweredQuestions->links() }}
            </div>
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
            const label = element.tagName === 'LABEL' 
                ? element 
                : element.nextElementSibling;

            if (label.style.textDecoration === 'line-through') {
                label.style.textDecoration = 'none';
            } else {
                label.style.textDecoration = 'line-through';
            }
        }

        document.querySelectorAll('.form-check-label').forEach((label) => {
            label.addEventListener('dblclick', function() {
                toggleStrikethroughOption(this);
            });
        });

        $('.btn-comment').on('click', function() {
            $('#comments').toggleClass('d-none');
        });

        $('.btn-resolution').on('click', function() {
            $('#resolution').toggleClass('d-none');
        });
    </script>
@endsection