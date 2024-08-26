@extends('app.layout')
@section('title') Caderno: {{ $answer->notebook->name }} @endsection
@section('content')

    <div class="col-sm-12 col-md-12 col-lg-12 card mb-3 p-5">
        <h3>{{ $answer->notebook->name }}</h3>
        <hr>
        
        <h6 class="card-title question">
            Questão: <b>{{ $answer->question->question_text }}</b>
        </h6>
        <div class="card-body">
            @foreach($answer->question->options as $option)
                <div class="form-check-question">
                    <input class="form-check-input" type="radio" name="option_id" value="{{ $option->id }}" id="option{{ $option->id }}" @checked($answer->option_id == $option->id)  disabled>
                    <label class="form-check-label" for="option{{ $option->id }}"> 
                        @if($option->is_correct)
                            <i class="bi bi-check2-all text-success"></i> 
                        @else
                            <i class="bi bi-x-circle text-danger"></i>
                        @endif
                        
                        {{ $option->option_text }} 
                    </label>
                </div>
            @endforeach
            <hr class="mt-5">
            <div class="text-center">
                <div class="btn-group w-50 mt-3" role="group">
                    <button type="button" class="btn btn-outline-success w-25" data-bs-toggle="modal" data-bs-target="#commentModal">Ver Resolução</button>
                    <a href="{{ route('answer', ['id' => $answer->notebook_id]) }}" class="btn btn-outline-primary w-25">Próxima</a>
                </div>
            </div>
        </div>

        <div class="modal fade" id="commentModal" tabindex="-1" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Resolução</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {!! $answer->question->comment_text !!}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection