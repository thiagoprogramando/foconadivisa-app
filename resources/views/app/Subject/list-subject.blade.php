@extends('app.layout')
@section('title') Conteúdo @endsection
@section('content')

    <div class="col-sm-12 col-md-12 col-lg-12 card mb-3 p-5">
        <div class="row g-0">

            <div class="col-12">
                <div class="btn-group" role="group">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#newPlan" class="btn btn-dark">Novo Conteúdo</button>
                    <button type="button" title="Excel" class="btn btn-outline-dark"><i class="bi bi-file-earmark-excel"></i></button>
                    <a href="{{ route('conteudos') }}" title="Recarregar" class="btn btn-outline-dark"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>

                <div class="modal fade" id="newPlan" tabindex="-1" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Detalhes:</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('create-subject') }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-floating mb-2">
                                                <input type="text" name="name" class="form-control" id="name" placeholder="Nome:" required>
                                                <label for="name">Nome</label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-floating mb-2">
                                                <textarea name="description" class="form-control" placeholder="Descrição" id="description" style="height: 100px;"></textarea>
                                                <label for="description">Descrição</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Fechar</button>
                                    <button type="submit" class="btn btn-dark">Criar Conteúdo</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-12 col-md-12 col-lg-12 mt-3">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Descrição</th>
                            <th scope="col" class="text-center">Tópicos</th>
                            <th scope="col" class="text-center">Questões</th>
                            <th scope="col" class="text-center">Opções</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subjects as $subject)
                            <tr>
                                <th scope="row">{{ $subject->id }}</th>
                                <td>{{ $subject->name }}</td>
                                <td>{{ strlen($subject->description) > 60 ? substr($subject->description, 0, 60) . '...' : $subject->description }}</td>
                                <td class="text-center">{{ $subject->countTopics() }}</td>
                                <td class="text-center">{{ $subject->countQuestions() }}</td>
                                <td class="text-center">
                                    <form action="{{ route('delete-subject') }}" method="POST" class="btn-group delete" role="group">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $subject->id }}">
                                        <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        <a href="{{ route('conteudo', ['id' => $subject->id]) }}" class="btn btn-outline-warning"><i class="bi bi-pen"></i></a>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>   
            </div>

        </div>
    </div>

@endsection