@extends('app.layout')
@section('title') Pesquisa:  @endsection
@section('content')

    <div class="col-sm-12 col-md-12 col-lg-12 card mb-3 p-2">
        <div class="row g-0">
            @if ($questions->count())
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 p-2">

                    <h1 class="card-title">Questões <span class="badge bg-dark text-white">FORAM LOCALIZADAS {{ $questions->count() }} QUESTÕES</span></h1> 
                    
                    
                    @foreach ($questions as $question)
                        <div class="card p-2 m-2 mb-5">
                            <div class="card-header">
                                <div class="row mb-3">
                                    <div class="col-12 col-sm-12 col-md-7 col-lg-7">
                                        <small><b>Conteúdo/Tópico:</b> 
                                            {{ $question->subject->parent->name ?? $question->subject->name }} |
                                            {{ $question->topic->name ?? '---' }}
                                        </small>
                                        <br>
                                        <small><b>{{ $question->responsesCount(Auth::user()->id, null, $question->id) }}</b> Resolvidas</small> <small class="text-success"><b>{{ $question->correctCount(Auth::user()->id, null, $question->id) }}</b> Acertos</small> <small class="text-danger"><b>{{ $question->wrogCount(Auth::user()->id, null, $question->id) }}</b> Erros</small>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-5 col-lg-5">
                                        <div class="btn-group">
                                            <a href="{{ route('ver-questao', ['id' => $question->id]) }}" target="_blank" class="btn btn-outline-dark" title="Dados da Questão"><i class="bi bi-pie-chart"></i> Dados</a>
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#addModal{{ $question->id }}" class="btn btn-outline-dark"><i class="ri-add-circle-line"></i> Adicionar ao caderno</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <h6 class="card-title p-2 mt-2 mb-3 bg-light"> 
                                    {!! $question->question_text !!}
                                </h6>

                                @php
                                    $letters = ['A', 'B', 'C', 'D', 'E'];
                                @endphp

                                <div class="bg-light p-3">
                                    @foreach($question->options as $index => $option)
                                    <p class="lead">{{ $letters[$index] }}) {{ $option->option_text }} </p>
                                    @endforeach
                                </div>
                                <hr>
                            </div>
                        </div>

                        <form action="{{ route('add-question-notebook') }}" method="POST">
                            @csrf
                            <input type="hidden" name="question_id" value="{{ $question->id }}">
                            <div class="modal fade" id="addModal{{ $question->id }}" tabindex="-1" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Adicionar questão</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                                    <div class="form-floating mb-3">
                                                        <select name="notebook_id" class="form-select" id="floatingSelect" aria-label="Floating label select example">
                                                            <option selected="">Escolha um caderno:</option>
                                                            @foreach ($notebooks as $notebook)
                                                            <option value="{{ $notebook->id }}">{{ $notebook->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <label for="floatingSelect">Cadernos</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer btn-group">
                                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Fechar</button>
                                            <button type="submit" class="btn btn-dark">Adicionar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @endforeach
                </div>
            @else
                <p class="text-center">Nenhuma questão encontrada!</p>
            @endif

        </div>
    </div>

@endsection