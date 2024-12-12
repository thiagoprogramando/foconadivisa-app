@extends('app.layout')
@section('title') Pesquisa:  @endsection
@section('content')

    <div class="col-sm-12 col-md-12 col-lg-12 card mb-3 p-2">
        <div class="row g-0">
            @if ($questions->count())
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 p-2">
                    <h1 class="card-title">Questões</h1>
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Conteúdo/Tópico</th>
                                    @if (Auth::user()->type == 1) <th scope="col" class="text-center">Opções</th> @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($questions as $question)
                                    <tr>
                                        <th scope="row">{{ $question->id }}</th>
                                        <td>{{ html_entity_decode(strip_tags($question->question_text)) }}</td>
                                        <td>
                                            <span class="badge bg-dark">{{ $question->subject->name }}</span>
                                            <span class="badge bg-primary">{{ $question->topic->name }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#addModal{{ $question->id }}" class="btn btn-dark"><i class="ri-add-circle-line"></i> Adicionar ao caderno</button>
                                            </div>
                                        </td>
                                    </tr>

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
                            </tbody>
                        </table>  
                    </div>
                    <hr>
                </div>
            @else
                <p class="text-center">Nenhuma questão encontrada!</p>
            @endif

        </div>
    </div>

@endsection