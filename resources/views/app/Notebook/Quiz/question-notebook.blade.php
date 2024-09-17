@extends('app.layout')
@section('title') Caderno: {{ $notebook->name }} @endsection
@section('content')

    <style>
        .form-check-question {
            font-size: 36px !important;
        }

        .form-check-label {
            font-size: 30px !important;
        }

        .question {
            font-size: 30px !important;
        }
    </style>

    <div class="col-sm-12 col-md-12 col-lg-12 card mb-3 p-5">
        <h3>{{ $notebook->name }}</h3>
        <hr>
        
        @if($unansweredQuestions && $unansweredQuestions->count() > 0)
            @foreach($unansweredQuestions as $notebookQuestion)
                @php
                    $question = $notebookQuestion->question;
                @endphp

                @if($question)
                    <h6 class="card-title question">
                        Questão: <b>{{ $question->question_text }}</b>
                    </h6>

                    <div class="card-body">
                        <form id="questionForm" method="POST" action="{{ route('submitAnswerAndNext', [$notebook->id, $notebookQuestion->id, $unansweredQuestions->currentPage()]) }}">
                            @csrf

                            <input type="hidden" name="notebook_question_id" value="{{ $notebookQuestion->id }}">

                            @foreach($question->options as $option)
                                <div class="form-check-question mb-3">
                                    <input class="form-check-input" type="radio" name="option_id" value="{{ $option->id }}" id="option{{ $option->id }}">
                                    <label class="form-check-label" for="option{{ $option->id }}"> {{ $option->option_text }} </label>
                                </div>
                            @endforeach

                            <hr class="mt-5">
                            <div class="text-center">
                                <div class="btn-group w-50 mt-3" role="group">
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
    </script>
@endsection