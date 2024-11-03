@extends('layout')
@section('ecommerce')

<div class="row mt-5">
    <div class="col-sm-12 offset-md-3 col-md-6 offset-lg-3 col-lg-6">
        <form action="" method="GET">
            <div class="input-group input-group-lg">
                <input type="search" name="name" class="form-control" placeholder="Pesquisar produtos" value="{{ request('name') }}"/>
                <button type="submit" class="btn btn-dark"><i class="bi bi-search"></i></button>
            </div>
        </form>
    </div>
</div>

<div class="container">
    <div class="row mt-5">
        @if($products->count() > 0)
            @foreach ($products as $product)
                <div class="col-6 col-sm-6 col-md-6 col-lg-3 mb-3">
                    <div class="card">
                        <img src="{{ asset('storage/'.$product->photo) }}" class="card-img-top" alt="{{ $product->name }}" style="width: 100%; max-height: 200px !important; min-height: 200px !important; object-fit: coven;"/>
                        <div class="card-body">
                            <p class="card-text mt-3">{{ $product->name }}</p>
                            <small>{{ Str::limit(strip_tags(html_entity_decode($product->description)), 90) }}</small>
                            <h5 class="card-title mb-3"><a href="#">R$ {{ number_format($product->value, 2, ',', '.') }}</a></h5>
                            
                            <form action="" method="POST" class="text-center card-footer">
                                @csrf
                                <input type="hidden" value="{{ $product->id }}" name="product_id">
                                <input type="hidden" value="1" name="qtd"/>
                                <a href="{{ route('order', ['id' => $product->id]) }}" class="btn btn-rounded btn-dark w-100 mb-3">COMPRAR AGORA</a>
                                <a href="{{ route('product', ['id' => $product->id]) }}"><b>Mais informações</b></a>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection

            