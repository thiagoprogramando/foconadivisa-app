@extends('app.layout')
@section('title') Vendas @endsection
@section('content')

    <div class="col-sm-12 col-md-12 col-lg-12 card mb-3 p-5">
        <div class="row g-0">

            <div class="col-12">
                <div class="btn-group" role="group">
                    <button type="button" title="Excel" class="btn btn-outline-dark"><i class="bi bi-file-earmark-excel"></i></button>
                    <a href="{{ route('pagamentos') }}" title="Recarregar" class="btn btn-outline-dark"><i class="bi bi-arrow-counterclockwise"></i></a>
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
@endsection