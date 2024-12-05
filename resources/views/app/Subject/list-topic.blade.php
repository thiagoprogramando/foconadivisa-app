@extends('app.layout')
@section('title') Tópicos @endsection
@section('content')

    <div class="col-sm-12 col-md-6 col-lg-6 mb-2">
        <div class="btn-group" role="group" aria-label="Basic outlined example">
            <button type="button" title="Filtros" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="bi bi-filter-circle"></i> Filtros
            </button>
            <a href="" class="btn btn-outline-dark" title="Excel">
                <i class="bi bi-file-earmark-excel"></i> Excel
            </a>  
            <a href="" title="Recarregar" class="btn btn-outline-dark"><i class="bi bi-arrow-counterclockwise"></i></a>

            <div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <form action="{{ route('topicos') }}" method="GET" class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Pesquisar</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-floating mb-2">
                                        <input type="text" name="name" class="form-control" id="name" placeholder="Nome:">
                                        <label for="name">Título</label>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <select id="swal-subject" name="subjects[]" placeholder="Escolha um Conteúdo (Opcional)">
                                        <option value="" selected>Escolha um Conteúdo (Opcional)</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                            </div>
                        </div>
                        <div class="modal-footer btn-group">
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-dark">Pesquisar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12 col-md-12 col-lg-12 card mb-2 p-2">
        <div class="row g-0">

            <div class="col-12 col-sm-12 col-md-12 col-lg-12 mt-3">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nome</th>
                                <th scope="col">Descrição</th>
                                <th scope="col" class="text-center">Questões</th>
                                <th scope="col" class="text-center">Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topics as $topic)
                                <tr>
                                    <th scope="row">{{ $topic->id }}</th>
                                    <td>
                                        {{ $topic->name }} <br>
                                        <span class="badge bg-dark">Conteúdo: {{ $topic->parent->name }}</span>
                                    </td>
                                    <td>{{ strlen($topic->description) > 60 ? substr($topic->description, 0, 60) . '...' : $topic->description }}</td>
                                    <td class="text-center">{{ $topic->countQuestions() }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('delete-topic') }}" method="POST" class="btn-group delete" role="group">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $topic->id }}">
                                            <button title="Excluir Tópico" type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                            <a title="Detalhes" href="{{ route('conteudo', ['id' => $topic->id]) }}" class="btn btn-outline-warning"><i class="bi bi-pen"></i></a>
                                            <a title="Nova Questão associada ao Tópico" href="{{ route('create-question', ['topic' => $topic->id]) }}" class="btn btn-dark"><i class="bi bi-plus-circle"></i></a>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>   
                </div>
            </div>

        </div>
    </div>

    <script>
        new TomSelect("#swal-subject",{
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            },
            maxItems: 1000
        });
    </script>
@endsection