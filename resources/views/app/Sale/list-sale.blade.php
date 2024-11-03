@extends('app.layout')
@section('title') Produtos - Vendas @endsection
@section('content')

    <div class="col-sm-12 col-md-12 col-lg-12 card mb-3 p-5">
        <div class="row g-0">

            <div class="col-12">
                <div class="btn-group" role="group">
                    <button type="button" title="Filtros" class="btn btn-dark modal-swal" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="bi bi-filter-circle"></i> Filtros
                    </button>
                    <a href="{{ route('sale-excel', request()->query()) }}" class="btn btn-outline-dark" title="Excel">
                        <i class="bi bi-file-earmark-excel"></i> Excel
                    </a>  
                    <a href="{{ route('produtos-vendas') }}" title="Recarregar" class="btn btn-outline-dark"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>

                <div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog">
                        <form action="{{ route('produtos-vendas') }}" method="GET" class="modal-content">
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
                                        <select id="swal-products" name="product_id" placeholder="Escolha um Produto">
                                            <option value="" selected>Escolha um Produto</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-2">
                                        <select name="payment_method" class="form-control" placeholder="Métodos de Pagamento">
                                            <option value="" selected>Métodos de Pagamento</option>
                                            <option value="PIX">PIX</option>
                                            <option value="CREDIT_CARD">Cartão de crédito</option>
                                            <option value="BOLETO">Boleto</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-2">
                                        <select name="payment_status" class="form-control" placeholder="Escolha um status">
                                            <option value="" selected>Escolha um status</option>
                                            <option value="1">Aprovado</option>
                                            <option value="0">Pendente</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-2">
                                        <select name="type" class="form-control" placeholder="Tipo">
                                            <option value="" selected>Tipo</option>
                                            <option value="1">Produto Digital</option>
                                            <option value="2">Simulado</option>
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
                                <th scope="col">Produto</th>
                                <th scope="col">Link de Pagamento</th>
                                <th scope="col" class="text-center">Status</th>
                                <th scope="col" class="text-center">Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sales as $sale)
                                <tr>
                                    <th scope="row">{{ $sale->id }}</th>
                                    <td>{{ $sale->user->name }}</td>
                                    <td>{{ $sale->product->name }}</td>
                                    <td> 
                                        <a href="{{ $sale->payment_url }}" target="_blank">
                                            <span class="badge bg-dark">{{ $sale->payment_url }}</span>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        {{ $sale->statusLabel() }} <br>
                                        {!! $sale->deliveryLabel() !!}
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('delete-sale') }}" method="POST" class="btn-group delete" role="group">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $sale->id }}">
                                            <button title="Excluir Venda" type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                            <a title="Confirmar Pagamento" href="{{ route('confirm-sale', ['id' => $sale->id]) }}" class="btn btn-outline-success"><i class="bi bi-check2-all"></i></a>
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

            var topic = new TomSelect("#swal-products", {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
            });
        });
    </script>
@endsection