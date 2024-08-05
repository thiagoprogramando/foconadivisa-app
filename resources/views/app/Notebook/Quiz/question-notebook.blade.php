@extends('app.layout')
@section('title') Caderno: {{ $notebook->name }} @endsection
@section('content')

    <div class="col-sm-12 col-md-12 col-lg-12 card mb-3 p-5">
        <h3>{{ $notebook->name }}</h3>
        <hr>
        
        @if($unansweredQuestions->count() > 0)
            @foreach($unansweredQuestions as $question)
                <h6 class="card-title question">
                    Questão: <b>{{ $question->question_text }}</b>
                </h6>
                <div class="card-body">
                    <form method="POST" action="{{ route('submitAnswerAndNext', [$notebook->id, $question->id, $unansweredQuestions->currentPage()]) }}">
                        @csrf
                        @foreach($question->options as $option)
                            <div class="form-check-question">
                                <input class="form-check-input" type="radio" name="option_id" value="{{ $option->id }}" id="option{{ $option->id }}">
                                <label class="form-check-label" for="option{{ $option->id }}"> {{ $option->option_text }} </label>
                            </div>
                        @endforeach
                        <hr class="mt-5">
                        <div class="text-center">
                            <div class="btn-group w-50 mt-3" role="group">
                                <a href="{{ route('caderno', ['id' => $notebook->id]) }}" class="btn btn-outline-danger w-25">SAIR</a>
                                <button type="submit" class="btn btn-outline-success w-25">RESPONDER</button>
                            </div>
                        </div>
                    </form>
                </div>
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
@endsection