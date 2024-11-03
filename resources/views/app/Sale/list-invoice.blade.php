@extends('app.layout')
@section('title') Planos - Vendas @endsection
@section('content')

    <div class="col-sm-12 col-md-12 col-lg-12 card mb-3 p-5">
        <div class="row g-0">

            <div class="col-12">
                <div class="btn-group" role="group">
                    <button type="button" title="Filtros" class="btn btn-dark modal-swal" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="bi bi-filter-circle"></i> Filtros
                    </button>
                    <a href="{{ route('invoice-excel', request()->query()) }}" class="btn btn-outline-dark" title="Excel">
                        <i class="bi bi-file-earmark-excel"></i> Excel
                    </a>  
                    <a href="{{ route('pagamentos') }}" title="Recarregar" class="btn btn-outline-dark"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>

                <div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog">
                        <form action="{{ route('vendas') }}" method="GET" class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Pesquisar</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-2">
                                        <select id="swal-users" name="user_id" placeholder="Escolha um usuário">
                                            <option value="" selected>Escolha um usuário</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-2">
                                        <select id="swal-plans" name="plan_id" placeholder="Escolha um Plano">
                                            <option value="" selected>Escolha um Plano</option>
                                            @foreach($plans as $plan)
                                                <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-2">
                                        <select name="payment_status" class="form-control" placeholder="Escolha uma Situação">
                                            <option value="" selected>Escolha uma Situação</option>
                                            <option value="1">Aprovado</option>
                                            <option value="0">Pendente</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Fechar</button>
                                <button type="submit" class="btn btn-dark">Pesquisar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-12 col-md-12 col-lg-12 mt-3">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Cliente</th>
                                <th scope="col">Plano</th>
                                <th scope="col">Link de Pagamento</th>
                                <th scope="col" class="text-center">Status</th>
                                <th scope="col" class="text-center">Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoices as $invoice)
                                <tr>
                                    <th scope="row">{{ $invoice->id }}</th>
                                    <td>{{ $invoice->labelUser->name }}</td>
                                    <td>{{ $invoice->labelPlan->name }}</td>
                                    <td> 
                                        <a href="{{ $invoice->payment_url }}" target="_blank">
                                            <span class="badge bg-dark">{{ $invoice->payment_url }}</span>
                                        </a>
                                    </td>
                                    <td class="text-center">{{ $invoice->statusLabel() }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('delete-invoice') }}" method="POST" class="btn-group delete" role="group">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $invoice->id }}">
                                            <button title="Excluir Fatura" type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                            <a title="Confirmar Pagamento" href="{{ route('confirm-invoice', ['id' => $invoice->id]) }}" class="btn btn-outline-success"><i class="bi bi-check2-all"></i></a>
                                            <a title="Abrir Link" href="{{ $invoice->payment_url }}" target="_blank" class="btn btn-dark"><i class="bi bi-credit-card-2-back"></i></a>
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
        $('.modal-swal').click(function(){
            var subject = new TomSelect("#swal-users", {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
            });

            var topic = new TomSelect("#swal-plans", {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
            });
        });
    </script>
@endsection