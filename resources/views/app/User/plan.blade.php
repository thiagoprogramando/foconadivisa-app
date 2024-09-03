@extends('app.layout')
@section('title') Planos @endsection
@section('content')

    <div class="col-sm-12 col-md-12 col-lg-12 card mb-3 p-5">
        <div class="row g-0">

            <div class="col-12">
                <div class="btn-group" role="group" aria-label="Basic outlined example">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#newPlan" class="btn btn-dark">Novo Plano</button>
                    <button type="button" title="Excel" class="btn btn-outline-dark"><i class="bi bi-file-earmark-excel"></i></button>
                    <a href="{{ route('planos') }}" title="Recarregar" class="btn btn-outline-dark"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>

                <div class="modal fade" id="newPlan" tabindex="-1" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Detalhes:</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('create-plan') }}" method="POST">
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
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-floating mb-2">
                                                <input type="number" name="value" class="form-control" id="value" placeholder="Valor:" required>
                                                <label for="value">Valor</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Fechar</button>
                                    <button type="submit" class="btn btn-dark">Criar Plano</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-12 col-md-12 col-lg-12 mt-3">
                <div class="card-deck mb-3 row">
                    @foreach ($plans as $plan)
                        <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="card card-price m-3 mb-4 box-shadow">
                                <div class="card-header text-center">
                                    <h4 class="my-0 font-weight-normal">{{ $plan->name }}</h4>
                                </div>
                                <div class="card-body">
                                    <h1 class="card-title pricing-card-title text-center">R$ {{ number_format($plan->value, 2, ',', '.') }} <small class="text-muted">/ mês</small></h1>
                                    <p class="text-justify mb-5">
                                        {{ strlen($plan->description) > 150 ? substr($plan->description, 0, 150) . '...' : $plan->description }}
                                    </p>
                                    <form action="{{ route('delete-plan') }}" method="POST" class="delete">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $plan->id }}">
                                        @if(Auth::user()->plan != $plan->id)
                                            <a href="{{ route('pay-plan', ['plan' => $plan->id]) }}" class="btn btn-outline-dark mt-2 w-100">Comprar Plano</a>
                                        @endif
                                        <a href="{{ route('plano', ['id' => $plan->id]) }}" class="btn btn-outline-warning mt-2 w-100">Editar plano</a>
                                        <button type="submit" class="btn btn-danger mt-2 w-100">Excluir plano</button>
                                    </form>                                    
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endsection