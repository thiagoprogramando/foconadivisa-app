@extends('app.layout')
@section('title') Plano: {{ $plan->name }} @endsection
@section('content')

    <div class="col-sm-12 col-md-12 col-lg-12 card mb-3 p-5">
        <h3>{{ $plan->name }}</h3>
        <hr>

        
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Conteúdos</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false" tabindex="-1">Tópicos</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false" tabindex="-1">Dados</button>
            </li>
        </ul>

        <div class="tab-content pt-2" id="myTabContent">

            <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
                <form action="{{ route('add-subject') }}" method="POST" class="row mt-3">
                    @csrf
                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                        <select id="swal-subject" name="subject_id[]" placeholder="Escolha um conteúdo">
                            <option value="" selected>Escolha um conteúdo</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-outline-success">Adicionar</button>
                            <button type="button" id="select-all-subjects" class="btn btn-dark">Selecionar Tudo</button>
                        </div>
                    </div>
                </form>

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
                            @foreach ($associatedSubjects as $associate)
                                <tr>
                                    <th scope="row">{{ $associate->id }}</th>
                                    <td>{{ $associate->name }}</td>
                                    <td>{{ $associate->description }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('delete-subject-associate') }}" method="POST" class="btn-group delete" role="group">
                                            @csrf
                                            <input type="hidden" name="subject_id" value="{{ $associate->id }}">
                                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
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
                <form action="{{ route('update-plan') }}" method="POST" class="row">
                    @csrf
                    <input type="hidden" name="id" value="{{ $plan->id }}">
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-floating mb-2">
                            <input type="text" name="name" class="form-control" id="name" placeholder="Nome:" value="{{ $plan->name }}">
                            <label for="name">Nome</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                        <div class="form-floating mb-2">
                            <input type="number" name="value" class="form-control" id="value" placeholder="Valor:" value="{{ $plan->value }}">
                            <label for="value">Valor</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                        <div class="form-floating mb-2">
                            <select name="type" class="form-select" id="type">
                                <option value="" selected>Forma de cobrança</option>
                                <option value="1" @if($plan->type == 1) selected @endif>Mensal</option>
                                <option value="2" @if($plan->type == 2) selected @endif>Anual</option>
                                <option value="3" @if($plan->type == 3) selected @endif>Vitalício</option>
                            </select>
                            <label for="type">Forma de cobrança</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-floating mb-2">
                            <textarea name="description" class="form-control" placeholder="Descrição" id="description" style="height: 100px;">{{ $plan->description }}</textarea>
                            <label for="description">Descrição</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-3 offset-md-9 col-lg-3 offset-lg-9">
                        <button type="submit" class="btn btn-outline-success w-100">Atualizar Plano</button>
                    </div>
                </form>
            </div>

            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <form action="{{ route('add-topic') }}" method="POST" class="row mt-3">
                    @csrf
                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                        <select id="swal-topic" name="topic_id[]" placeholder="Escolha um tópico">
                            <option value="" selected>Escolha um tópico</option>
                            @foreach($topics as $topic)
                                <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-outline-success">Adicionar</button>
                            <button type="button" id="select-all-topics" class="btn btn-dark">Selecionar Tudo</button>
                        </div>
                    </div>
                </form>

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
                            @foreach ($associatedTopics as $associate)
                                <tr>
                                    <th scope="row">{{ $associate->id }}</th>
                                    <td>{{ $associate->name }}</td>
                                    <td>{{ strlen($topic->description) > 100 ? substr($topic->description, 0, 100) . '...' : $topic->description }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('delete-topic-associate') }}" method="POST" class="btn-group delete" role="group">
                                            @csrf
                                            <input type="hidden" name="topic_id" value="{{ $associate->id }}">
                                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
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
        
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            var subject = new TomSelect("#swal-subject",{
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                maxItems: 1000
            });

            var topic = new TomSelect("#swal-topic",{
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                maxItems: 1000
            });

            document.getElementById('select-all-subjects').addEventListener('click', function () {
                var allOptions = Array.from(document.querySelectorAll('#swal-subject option')).map(option => option.value);
                subject.setValue(allOptions);
            });

            document.getElementById('select-all-topics').addEventListener('click', function () {
                var allOptions = Array.from(document.querySelectorAll('#swal-topic option')).map(option => option.value);
                topic.setValue(allOptions);
            });
        });
    </script>

@endsection