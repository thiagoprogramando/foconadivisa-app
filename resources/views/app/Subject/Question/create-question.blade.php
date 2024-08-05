@extends('app.layout')
@section('title') Cadastro de Questões @endsection
@section('content')

    <div class="col-sm-12 col-md-12 col-lg-12 card mb-3 p-5">
        <h3>{{ $subject->name }}</h3>
        <hr>
        
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false" tabindex="-1">Questão</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="option-tab" data-bs-toggle="tab" data-bs-target="#option" type="button" role="tab" aria-controls="option" aria-selected="false" tabindex="-1">Respostas</button>
            </li>
        </ul>

        <div class="tab-content pt-2" id="myTabContent">

            <div class="tab-pane active" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <form action="{{ route('update-question') }}" method="POST" class="row mt-3 m-5">
                    @csrf
                    <input type="hidden" name="id" value="{{ $question->id }}">

                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-2">
                        <select id="swal-topic" name="topic_id" placeholder="Escolha um tópico (Opcional)">
                            <option value="{{ $question->topic_id }}" selected>@if(empty($question->topic_id)) Escolha um tópico (Opcional) @else {{ $question->topic->name }} @endif</option>
                            @foreach($topics as $topic)
                                <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-floating mb-2">
                            <textarea name="question_text" class="form-control" placeholder="Questão" id="question" style="height: 100px;">{{ $question->question_text }}</textarea>
                            <label for="question">Questão:</label>
                        </div>
                    </div>

                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <button type="submit" class="btn btn-outline-success w-100 mb-2">Atualizar</button>
                        <a href="{{ route('create-question', ['subject' => $subject->id]) }}" class="btn btn-outline-primary w-100 mb-2">Nova Questão</a>
                        <a href="{{ route('conteudo', ['id' => $subject->id]) }}" class="btn btn-outline-primary w-100 mb-2">Ver Conteúdo</a>
                    </div>
                </form>
            </div>

            <div class="tab-pane fade" id="option" role="tabpanel" aria-labelledby="option-tab">

                <div class="btn-group mt-3" role="group" aria-label="Basic outlined example">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#newOption" class="btn btn-outline-primary">Nova Resposta</button>
                    <button type="button" class="btn btn-outline-primary">PDF</button>
                    <button type="button" class="btn btn-outline-primary">Excel</button>
                </div>

                <div class="modal fade" id="newOption" tabindex="-1" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Detalhes:</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('create-option') }}" method="POST">
                                @csrf
                                <input type="hidden" name="question_id" value="{{ $question->id }}">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-floating mb-2">
                                                <textarea name="option_text" class="form-control" placeholder="Resposta:" id="option_text" style="height: 100px;"></textarea>
                                                <label for="option_text">Resposta:</label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                            <select name="is_correct" class="form-control">
                                                <option value="" selected>É a resposta correta?</option>
                                                <option value="1">Sim</option>
                                                <option value="0">Não</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Fechar</button>
                                    <button type="submit" class="btn btn-outline-success">Criar Resposta</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <table class="table table-hover mt-5">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Resposta</th>
                            <th scope="col">Correta</th>
                            <th scope="col" class="text-center">Opções</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($options as $option)
                            <tr>
                                <th scope="row">{{ $option->id }}</th>
                                <td>{{ $option->option_text }}</td>
                                <td>@if($option->is_correct == 1) Correta @else Incorreta @endif</td>
                                <td class="text-center">
                                    <form action="{{ route('delete-option') }}" method="POST" class="btn-group delete" role="group">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $option->id }}">
                                        <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table> 

            </div>
        </div>
        
    </div>

    <script>
        new TomSelect("#swal-topic",{
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });
    </script>

@endsection