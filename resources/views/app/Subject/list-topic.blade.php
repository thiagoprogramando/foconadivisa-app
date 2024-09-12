@extends('app.layout')
@section('title') Tópicos @endsection
@section('content')

    <div class="col-sm-12 col-md-12 col-lg-12 card mb-3 p-5">
        <div class="row g-0">

            <div class="col-12">
                <div class="btn-group" role="group">
                    <button type="button" title="Excel" class="btn btn-outline-dark"><i class="bi bi-file-earmark-excel"></i></button>
                    <a href="{{ route('topicos') }}" title="Recarregar" class="btn btn-outline-dark"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>
            </div>

            <div class="col-12 col-sm-12 col-md-12 col-lg-12 mt-3">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nome</th>
                                <th scope="col">Descrição</th>
                                <th scope="col" class="text-center">Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topics as $topic)
                                <tr>
                                    <th scope="row">{{ $topic->id }}</th>
                                    <td>{{ $topic->name }}</td>
                                    <td>{{ strlen($topic->description) > 100 ? substr($topic->description, 0, 100) . '...' : $topic->description }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('delete-topic') }}" method="POST" class="btn-group delete" role="group">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $topic->id }}">
                                            <button title="Excluir Tópico" type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                            <a title="Detalhes" href="{{ route('conteudo', ['id' => $topic->subject_id]) }}" class="btn btn-outline-warning"><i class="bi bi-pen"></i></a>
                                            <a title="Nova Questão associada ao Tópico" href="{{ route('create-question', ['subject' => $topic->subject_id, 'topic' => $topic->id]) }}" class="btn btn-dark"><i class="bi bi-plus-circle"></i></a>
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