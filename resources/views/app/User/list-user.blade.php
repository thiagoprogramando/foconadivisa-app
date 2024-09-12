@extends('app.layout')
@section('title') Conteúdo @endsection
@section('content')

    <div class="col-sm-12 col-md-12 col-lg-12 card mb-3 p-5">
        <div class="row g-0">

            <div class="col-12">
                <div class="btn-group" role="group">
                    <button type="button" title="Excel" class="btn btn-outline-dark"><i class="bi bi-file-earmark-excel"></i></button>
                    <a href="{{ route('usuarios') }}" title="Recarregar" class="btn btn-outline-dark"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>
            </div>

            <div class="col-12 col-sm-12 col-md-12 col-lg-12 mt-3">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nome</th>
                                <th scope="col">Dados</th>
                                <th scope="col">Plano</th>
                                <th scope="col">Tipo</th>
                                <th scope="col" class="text-center">Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <th scope="row">{{ $user->id }}</th>
                                    <td>{{ $user->name }}</td>
                                    <td><span class="badge bg-dark"><i class="bi bi-envelope me-1"></i></i>{{ $user->phone }} - {{ $user->email }}</span><br> {{ $user->cpfcnpj }}</td>
                                    <td>{{ $user->labelPlan->name }}</td>
                                    <td>{{ $user->typeLabel() }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('delete-user') }}" method="POST" class="btn-group delete" role="group">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $user->id }}">
                                            <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#update{{ $user->id }}" class="btn btn-outline-warning"><i class="bi bi-pen"></i></button>
                                        </form>

                                        <div class="modal fade" id="update{{ $user->id }}" tabindex="-1" aria-hidden="true" style="display: none;">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Detalhes:</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('update-profile') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $user->id }}">
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                                                    <div class="form-floating mb-2">
                                                                        <input type="text" name="name" class="form-control" id="name" placeholder="Nome:" value="{{ $user->name }}" readonly>
                                                                        <label for="name">Nome</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                                                    <div class="form-floating mb-2">
                                                                        <input type="email" name="email" class="form-control" id="email" placeholder="Email:" value="{{ $user->email }}">
                                                                        <label for="email">Email</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                                                    <select name="type" class="form-control">
                                                                        <option value="" selected>Tipo</option>
                                                                        <option value="1" @if($user->type == 1) selected @endif>Administrador</option>
                                                                        <option value="2" @if($user->type == 2) selected @endif>Colaborador</option>
                                                                        <option value="0" @if($user->type == 0) selected @endif>Usuário</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Fechar</button>
                                                            <button type="submit" class="btn btn-outline-success">Atualizar usuário</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
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