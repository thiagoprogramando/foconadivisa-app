@extends('layout')
@section('ecommerce')

<style>
    .card {
        padding: 0;
    }

    .carousel {
        padding: 0;
        margin: 0;
    }
</style>

<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                @foreach ($banners as $index => $banner)
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $index }}"  @if($index == 0) class="active" aria-current="true" @endif></button>
                @endforeach
            </div>

            <div class="carousel-inner">
                @foreach ($banners as $index => $banner)
                    <div class="carousel-item @if($index == 0) active @endif">
                        <img src="{{ asset('storage/'.$banner->file) }}" class="d-block w-100" alt="{{ $banner->name }}">
                    </div>
                @endforeach
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    <div class="col-12 col-sm-12 col-md-12 col-lg-12 card">
        <div class="container">
            <div class="row mb-2">
                @if($products->count() > 0)
                    @foreach ($products as $product)
                        <div class="col-12 col-sm-12 col-md-4 col-lg-3 mb-3">
                            <div class="card">
                                <img src="{{ asset('storage/'.$product->photo) }}" class="card-img-top" alt="{{ $product->name }}" style="width: 100%; max-height: 200px !important; min-height: 200px !important; object-fit: coven;"/>
                                <div class="card-body">
                                    <p class="card-text mt-3">{{ $product->name }}</p>
                                    <small>{{ Str::limit(strip_tags(html_entity_decode($product->description)), 40) }}</small>
                                    <h5 class="card-title mb-1"><a href="#">R$ {{ number_format($product->value, 2, ',', '.') }}</a></h5>
                                    
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
    </div>
</div>
@endsection

            