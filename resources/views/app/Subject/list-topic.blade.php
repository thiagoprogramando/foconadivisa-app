@extends('app.layout')
@section('title') Tópicos @endsection
@section('content')

    <div class="col-sm-12 col-md-12 col-lg-12 card mb-3 p-5">
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

@endsection