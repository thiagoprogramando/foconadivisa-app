@extends('app.layout')
@section('title') Caderno: {{ $answer->notebook->name }} @endsection
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

        .iscorrect {
            background-color: rgba(0, 128, 0, 0.2);
        }

        .isincorrect {
            background-color: rgba(255, 0, 0, 0.2);
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
        <div class="card-header">
            <div class="row mb-3">
                <div class="col-12 col-sm-12 col-md-7 col-lg-7">
                    <h6 class="question">
                        Questão: {{ $currentQuestionNumber }} de {{ $totalQuestions }}
                    </h6>
                    @if($answer->question->subject || $answer->question->topic)
                        <small><b>Conteúdo/Tópico:</b> 
                            {{ $answer->question->subject->parent->name ?? $answer->question->subject->name }} |
                            {{ $answer->question->topic->name ?? '---' }}
                        </small><br>
                    @endif                    
                    <small><b>{{ $answer->question->responsesCount(Auth::user()->id, $answer->notebook->id) }}</b> Resolvidas</small> <small class="text-success"><b>{{ $answer->question->correctCount(Auth::user()->id, $answer->notebook->id) }}</b> Acertos</small> <small class="text-danger"><b>{{ $answer->question->wrogCount(Auth::user()->id, $answer->notebook->id) }}</b> Erros</small>
                </div>
                <div class="col-12 col-sm-12 col-md-5 col-lg-5">
                    <div class="btn-group">
                        <a href="{{ route('ver-questao', ['id' => $answer->question->id]) }}" target="_blank" class="btn btn-outline-dark" title="Dados da Questão"><i class="bi bi-pie-chart"></i> Dados</a>
                        <a href="{{ route('caderno-filtros', ['id' => $answer->notebook->id]) }}" class="btn btn-outline-dark" title="Modificar filtros"><i class="bx bx-filter"></i> Filtros</a>
                        <button class="btn btn-outline-dark" title="Relatar problema" data-bs-toggle="modal" data-bs-target="#newTicket"><i class="ri-alarm-warning-fill"></i> Relatar problema</button>
                        <button class="btn btn-outline-dark btn-resolution" title="Comentário do Professor"><i class="bx bxs-book-reader"></i></button>
                        <button type="button" class="btn btn-outline-dark btn-comment" title="Comentários sobre a questão"><i class="bi bi-chat-square-text"></i></button>
                    </div>

                    <div class="modal fade" id="newTicket" tabindex="-1" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Detalhes:</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('create-ticket') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="question_id" value="{{ $answer->question->id }}">
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                                <textarea name="comment" class="form-control" rows="4" placeholder="Descreva o problema:"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Fechar</button>
                                        <button type="submit" class="btn btn-outline-success">Enviar</button>
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
                <p class="lead">Resolução - <small style="font-size: 14px;">Data do comentário: {{ $answer->question->updated_at->format('d/m/Y') }}</small></p>
                {!! $answer->question->comment_text !!}
            </div>

            <div id="comments" class="d-none p-3 mt-3">
                <p class="lead">Comentários</p>

                <form action="{{ route('create-comment') }}" method="POST" class="d-flex btn-group w-50 mb-3">
                    @csrf
                    <input type="hidden" name="question_id" value="{{ $answer->question->id }}">
                    <input type="text" name="comment" class="form-control" placeholder="Faça seu comentário..." required>
                    <button class="btn btn-dark"><i class="bi bi-plus-circle"></i></button>
                </form>

                @foreach ($answer->question->comments as $comment)
                    <div class="alert alert-light border-light alert-dismissible fade show" role="alert">
                        {{ $comment->user->name }} - <small>{{ $comment->created_at->format('d/m/Y') }}</small> <br> {{ $comment->comment }}
                        <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>
                @endforeach
            </div>

            <h6 class="card-title p-2 mt-2 mb-3 bg-light"> <a href="">#{{ $answer->question->id }}</a> {!! $answer->question->question_text !!} </h6>
            <div class="bg-light p-3">
                @php
                    $letters = ['A', 'B', 'C', 'D', 'E'];
                @endphp
                @foreach($answer->question->options as $index => $option)
                    <div class="form-check-questio form-question option-container mb-3 p-2 w-100 @if($option->is_correct) iscorrect @endif @if($answer->option_id == $option->id && !$option->is_correct) isincorrect @endif">
                        <input class="form-check-input" type="radio" name="option_id" value="{{ $option->id }}" id="option{{ $option->id }}" @checked($answer->option_id == $option->id)  disabled>
                        <label class="form-check-label" for="option{{ $option->id }}"> 

                            @if($option->is_correct)
                                <i class="bi bi-check2-all text-success"></i> 
                            @else
                                <i class="bi bi-x-circle text-danger"></i>
                            @endif

                            <span class="option-letter">{{ $letters[$index] }})</span> {{ $option->option_text }}
                        </label>
                    </div>
                @endforeach
            </div>
            <hr class="mt-5">
            <div class="text-center">
                <div class="btn-group w-50 mt-3" role="group">
                    <button type="button" class="btn btn-dark btn-resolution">Ver Resolução</button>
                    <a href="{{ route('answer', ['id' => $answer->notebook_id]) }}" class="btn btn-outline-primary">Próxima</a>
                </div>
            </div>
        </div>
    </div>

    <script>
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
    </script>
@endsection