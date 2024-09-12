@extends('app.layout')
@section('title') Conteúdo: {{ $subject->name }} @endsection
@section('content')

    <div class="col-sm-12 col-md-12 col-lg-12 card mb-3 p-5">
        <h3>{{ $subject->name }}</h3>
        <hr>

        
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Tópicos</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false" tabindex="-1">Questões</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false" tabindex="-1">Dados</button>
            </li>
        </ul>

        <div class="tab-content pt-2" id="myTabContent">
            
            <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
                
                <div class="btn-group mt-3" role="group" aria-label="Basic outlined example">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#newTopic" class="btn btn-outline-primary">Novo Tópico</button>
                    <button type="button" class="btn btn-outline-primary">PDF</button>
                    <button type="button" class="btn btn-outline-primary">Excel</button>
                </div>

                <div class="modal fade" id="newTopic" tabindex="-1" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Detalhes:</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('create-topic') }}" method="POST">
                                @csrf
                                <input type="hidden" name="subject_id" value="{{ $subject->id }}">
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
                                    <button type="submit" class="btn btn-outline-success">Criar Tópico</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover mt-5">
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

            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                
                <div class="btn-group mt-3" role="group" aria-label="Basic outlined example">
                    <a href="{{ route('create-question', ['subject' => $subject->id]) }}" target="_blank" class="btn btn-outline-primary">Nova Questão</a>
                    <button type="button" class="btn btn-outline-primary">PDF</button>
                    <button type="button" class="btn btn-outline-primary">Excel</button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover mt-5">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Questão</th>
                                <th scope="col">Tópico</th>
                                <th scope="col" class="text-center">Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($questions as $question)
                                <tr>
                                    <th scope="row">{{ $question->id }}</th>
                                    <td>{{ $question->question_text }}</td>
                                    <td>{{ optional($question->topic)->name ?? '---' }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('delete-question') }}" method="POST" class="btn-group delete" role="group">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $question->id }}">
                                            <a href="{{ route('questao', ['id' => $question->id]) }}" class="btn btn-outline-warning"><i class="bi bi-pen"></i></a>
                                            <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table> 
                </div>
            </div>

            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <form action="{{ route('update-subject') }}" method="POST" class="row">
                    @csrf
                    <input type="hidden" name="id" value="{{ $subject->id }}">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-floating mb-2">
                            <input type="text" name="name" class="form-control" id="name" placeholder="Nome:" value="{{ $subject->name }}">
                            <label for="name">Nome</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-floating mb-2">
                            <textarea name="description" class="form-control" placeholder="Descrição" id="description" style="height: 100px;">{{ $subject->description }}</textarea>
                            <label for="description">Descrição</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-3 offset-md-9 col-lg-3 offset-lg-9">
                        <button type="submit" class="btn btn-outline-success w-100">Atualizar Conteúdo</button>
                    </div>
                </form>
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