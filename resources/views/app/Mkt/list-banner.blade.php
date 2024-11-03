@extends('app.layout')
@section('title') MKT - Banners @endsection
@section('content')

<div class="col-sm-12 col-md-12 col-lg-12 card mb-3 p-5">
    <div class="row g-0">

        <div class="col-12">
            <div class="btn-group" role="group">
                <button type="button" data-bs-toggle="modal" data-bs-target="#newPlan" class="btn btn-dark modal-swal">Novo Banner</button>
                <a href="{{ route('banners') }}" title="Recarregar" class="btn btn-outline-dark"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>

            <div class="modal fade" id="newPlan" tabindex="-1" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Detalhes:</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('create-banner') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-8 col-lg-8">
                                        <div class="form-floating mb-2">
                                            <input type="text" name="name" class="form-control" id="name" placeholder="Nome:">
                                            <label for="name">Nome</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                        <div class="form-floating mb-2">
                                            <input type="text" name="description" class="form-control" id="description" placeholder="Descrição">
                                            <label for="description">Descrição</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-floating mb-2">
                                            <input type="file" name="file" class="form-control" id="file" placeholder="Arquivo" required>
                                            <label for="file">Arquivo <span class="badge bg-danger">Recomendação: 2000 x 300</span></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer btn-group">
                                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Fechar</button>
                                <button type="submit" class="btn btn-dark">Criar Banner</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 row p-3">

            @foreach ($banners as $banner)
                <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="card">
                        <img src="{{ asset('storage/'.$banner->file) }}" class="card-img-top" alt="{{ $banner->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $banner->name }}</h5>
                            <p class="card-text">{{ $banner->description }}</p>
                        </div>
                        <div class="card-footer btn-group">
                            <a href="{{ route('delete-banner', ['id' => $banner->id]) }}" class="btn btn-danger"><i class="bi bi-trash"></i></a>
                            <a href="{{ asset('storage/'.$banner->file) }}" target="_blank" class="btn btn-dark"><i class="bi bi-arrow-bar-right"></i></a>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>

    </div>
</div>

@endsection