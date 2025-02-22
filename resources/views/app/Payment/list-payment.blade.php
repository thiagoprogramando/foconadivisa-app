@extends('app.layout')
@section('title') Pagamentos @endsection
@section('content')

    <div class="col-sm-12 col-md-12 col-lg-12 card mb-3 p-2">
        <div class="row g-0">

            <div class="col-12 col-sm-12 col-md-12 col-lg-12 mt-3">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Plano</th>
                                <th scope="col">Valor</th>
                                <th scope="col">Acesso ao Pagamento</th>
                                <th scope="col">Vencimento</th>
                                <th scope="col" class="text-center">Status</th>
                                <th scope="col" class="text-center">Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $payment)
                                <tr>
                                    <th scope="row">{{ $payment->id }}</th>
                                    <td>{{ $payment->labelPlan->name }}</td>
                                    <td>R$ {{ number_format($payment->value, 2, ',', '.') }}</td>
                                    <td> 
                                        <a href="{{ $payment->payment_url }}">
                                            <span class="badge bg-dark">{{ $payment->payment_url }}</span>
                                        </a> 
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($payment->due_date)->format('d/m/Y') }}</td>
                                    <td class="text-center">{{ $payment->statusLabel() }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('delete-payment') }}" method="POST" class="btn-group delete" role="group">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $payment->id }}">
                                            @if (Auth::user()->type == 1)
                                                <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                            @endif
                                            <a href="{{ $payment->payment_url }}" target="_blank" class="btn btn-dark"><i class="bi bi-credit-card-2-back"></i></a>
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