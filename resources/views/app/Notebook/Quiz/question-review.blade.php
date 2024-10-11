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

        #resolution {
            border: 1px solid #000;
            border-radius: 5px;
        }
    </style>

    <div class="col-sm-12 col-md-12 col-lg-12 card mb-3 p-3">
        <div class="card-header">
            <div class="row mb-3">
                <div class="col-12 col-sm-12 col-md-8 col-lg-8">
                    <h6 class="question">
                        Questão: {{ $currentQuestionNumber }} de {{ $totalQuestions }}
                    </h6>                    
                    <small><b>Conteúdo/Tópico:</b> {{ $answer->question->subject->name }}</small> <br>
                    <small><b>{{ $answer->question->responsesCount(Auth::user()->id, $answer->notebook->id) }}</b> Resolvidas</small> <small class="text-success"><b>{{ $answer->question->correctCount(Auth::user()->id, $answer->notebook->id) }}</b> Acertos</small> <small class="text-danger"><b>{{ $answer->question->wrogCount(Auth::user()->id, $answer->notebook->id) }}</b> Erros</small>
                </div>
                <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="btn-group">
                        <a class="btn btn-outline-dark" title="Dados da Questão"><i class="bi bi-pie-chart"></i> Dados</a>
                        <a href="{{ route('caderno-filtros', ['id' => $answer->notebook->id]) }}" class="btn btn-outline-dark" title="Modificar filtros"><i class="bx bx-filter"></i> Filtros</a>
                        <button class="btn btn-outline-dark btn-resolution" title="Comentário do Professor"><i class="bx bxs-book-reader"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">

            <div id="resolution" class="d-none p-3 mt-3 mb-3">
                <p class="lead">Resolução</p>
                {!! $answer->question->comment_text !!}
            </div>

            <h6 class="card-title p-2 mt-2 mb-3 bg-light"> <a href="">#{{ $answer->question->id }}</a> {!! $answer->question->question_text !!} </h6>
            <div class="bg-light p-3">
                @php
                    $letters = ['A', 'B', 'C', 'D', 'E'];
                @endphp
                @foreach($answer->question->options as $index => $option)
                    <div class="form-check-questio form-question option-container mb-3 p-2 w-100">
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
        $('.btn-resolution').on('click', function() {
            $('#resolution').toggleClass('d-none');
        });
    </script>
@endsection