@extends('app.layout')
@section('title') Minhas Compras @endsection
@section('content')

    <div class="col-sm-12 col-md-12 col-lg-12 card mb-3 p-2">
        <div class="row g-0">

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