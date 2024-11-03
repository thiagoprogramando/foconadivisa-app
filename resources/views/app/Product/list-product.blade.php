@extends('app.layout')
@section('title') Produtos Digitais @endsection
@section('content')

    <div class="col-sm-12 col-md-12 col-lg-12 card mb-3 p-5">
        <div class="row g-0">

            <div class="col-12">
                <div class="btn-group" role="group">
                    <a href="{{ route('form-create-product') }}" class="btn btn-dark">Novo Produto</a>
                    <a href="{{ route('produtos') }}" title="Recarregar" class="btn btn-outline-dark"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>
            </div>

            <div class="col-12 col-sm-12 col-md-12 col-lg-12 mt-3">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Produto</th>
                                <th scope="col">Link de Compra</th>
                                <th scope="col" class="text-center">Valor</th>
                                <th scope="col" class="text-center">Visualizações</th>
                                <th scope="col" class="text-center">Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <th scope="row">{{ $product->id }}</th>
                                    <td> {{ $product->name }}</td>
                                    <td> 
                                        <span class="badge bg-dark"><a href="{{ env('APP_URL') }}order/2" target="_blank">{{ env('APP_URL') }}order/2</a></span>
                                    </td>
                                    <td class="text-center">R$ {{ number_format($product->value, 2, ',', '.') }}</td>
                                    <th scope="row" class="text-center">{{ $product->views }}</th>
                                    <td class="text-center">
                                        <form action="{{ route('delete-product') }}" method="POST" class="btn-group delete" role="group">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $product->id }}">
                                            <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                            <a href="{{ route('form-update-product', ['id' => $product->id]) }}" class="btn btn-outline-warning"><i class="bi bi-pen"></i></a>
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