@extends('app.layout')
@section('title') Pesquisa:  @endsection
@section('content')

    <div class="col-sm-12 col-md-12 col-lg-12 card mb-3 p-5">
        <div class="row g-0">

            <div class="col-12">
                <div class="btn-group" role="group">
                    <button type="button" title="Excel" class="btn btn-outline-dark"><i class="bi bi-file-earmark-excel"></i></button>
                    <a href="{{ route('topicos') }}" title="Recarregar" class="btn btn-outline-dark"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>
            </div>

            @if($notebooks->count())
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 p-5">
                    <h1 class="card-title">Meus cadernos</h1>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Conteúdos</th>
                                    <th scope="col" class="text-center">Progresso</th>
                                    <th scope="col" class="text-center">Questões</th>
                                    <th scope="col" class="text-center">Opções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($notebooks as $notebook)
                                    <tr>
                                        <th scope="row">{{ $notebook->id }}</th>
                                        <td>{{ $notebook->name }}</td>
                                        <td>
                                            @foreach ($notebook->getSubjectsNames() as $subject)
                                                <span class="badge bg-dark">{{ $subject }}</span>
                                            @endforeach

                                            @foreach ($notebook->getTopicsNames() as $topic)
                                                <span class="badge bg-secondary">{{ $topic }}</span>
                                            @endforeach
                                        </td>
                                        <td class="text-center">{{ $notebook->percentage }}%</td>
                                        <td class="text-center">{{ $notebook->countQuestions() }}</td>
                                        <td class="text-center">
                                            <form action="{{ route('delete-notebook') }}" method="POST" class="btn-group delete" role="group">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $notebook->id }}">
                                                <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                                <a href="{{ route('caderno', ['id' => $notebook->id]) }}" class="btn btn-outline-success"><i class="bi bi-arrow-bar-right"></i></a>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>  
                    </div>
                </div>
            @endif

            @if($subjects->count())
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 p-5">
                    <h1 class="card-title">Conteúdos</h1>
                    <div class="table-responsive">
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
            @endif

            @if($topics->count())
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 p-5">
                    <h1 class="card-title">Tópicos</h1>
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
                                                <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>   
                </div>
            @endif

        </div>
    </div>

@endsection