@extends('layout')
@section('ecommerce')
<div class="row mt-5">
    <div class="col-12 col-sm-12 col-md-5 col-lg-4 mb-3">
        <img src="{{ asset('storage/'.$product->photo) }}" class="d-block w-100" alt="{{ $product->name }}" style="object-fit: contain; max-height: 800px; width: auto;"/>
    </div>

    <div class="col-12 col-sm-12 col-md-7 col-lg-8 card p-5">
        <h4 class="fw-light"><b>{{ $product->name }}</b></h4>
        <h3 class="fw-light mt-3"><b>R$ {{ number_format($product->value, 2, ',', '.') }}</b></h3>
        
        <div class="mb-3">
            <a class="btn btn-dark" href="{{ route('order', ['id' => $product->id]) }}"><i class="bi bi-cart-plus"></i> COMPRAR AGORA</a>
        </div>
        
        <p class="lead mt-3">
            <b>Descrição</b> <br>
            {!! $product->description !!}
        </p>
    </div>
</div>
@endsection

    