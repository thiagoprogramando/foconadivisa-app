@extends('app.layout')
@section('title') Minhas Compras @endsection
@section('content')

    <div class="col-sm-12 col-md-12 col-lg-12 card mb-3 p-5">
        <div class="row g-0">

            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <div class="btn-group" role="group">
                    <a href="{{ route('sale-excel', request()->query()) }}" class="btn btn-outline-dark" title="Excel">
                        <i class="bi bi-file-earmark-excel"></i> Excel
                    </a>  
                    <a href="{{ route('produtos-vendas') }}" title="Recarregar" class="btn btn-outline-dark"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>
            </div>

            <div class="col-12 col-sm-12 col-md-12 col-lg-12 mt-3">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Produto</th>
                                <th scope="col" class="text-center">Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sales as $sale)
                                <tr>
                                    <th scope="row">{{ $sale->id }}</th>
                                    <td>{{ $sale->product->name }}</td>
                                    <td class="text-center">
                                        <a title="Acessar" href="{{ asset('storage/'.$sale->product->file) }}" target="_blank" class="btn btn-outline-success"><i class="bi bi-check2-all"></i> Acessar</a>
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