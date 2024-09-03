@extends('app.layout')
@section('title') Perfil @endsection
@section('content')

    <div class="col-sm-12 col-md-12 col-lg-12 card mb-3 p-5">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-4 col-lg-4 text-center">
                <div class="profile-photo">
                    @if(Auth::user()->photo)
                        <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="User Photo" class="img-thumbnail w-50">
                    @else
                        <img src="{{ asset('template/img/components/profile.png') }}" alt="Default Photo" class="img-thumbnail w-50">
                    @endif
                </div>

                <button class="btn btn-primary mt-3" id="change-photo-button">Trocar foto de perfil</button>

                <form action="{{ route('update-profile') }}" method="POST" enctype="multipart/form-data" id="photo-upload-form" class="d-none">
                    @csrf
                    <input type="hidden" name="id" value="{{ Auth::user()->id }}">
                    <input type="file" name="photo" id="photo-input" accept="image/*" onchange="document.getElementById('photo-upload-form').submit();">
                </form>
            </div>

            <div class="col-12 col-sm-12 col-md-8 col-lg-8">
                <form action="{{ route('update-profile') }}" method="POST" class="row g-0">
                    @csrf
                    <input type="hidden" name="id" value="{{ Auth::user()->id }}">
                    <div class="col-12 col-sm-12 col-md-8 col-lg-8">
                        <div class="form-floating mb-3 m-1">
                            <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}" id="name" placeholder="Nome:" required>
                            <label for="name">Nome</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="form-floating mb-3 m-1">
                            <input type="number" name="cpfcnpj" class="form-control" value="{{ Auth::user()->cpfcnpj }}" id="cpfcnpj" placeholder="CPF ou CNPJ:" required>
                            <label for="cpfcnpj">CPF ou CNPJ:</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-8 col-lg-8">
                        <div class="form-floating mb-3 m-1">
                            <input type="email" name="email" class="form-control" value="{{ Auth::user()->email }}" id="email" placeholder="Email:" required>
                            <label for="email">Email</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="form-floating mb-3 m-1">
                            <input type="number" name="phone" class="form-control" value="{{ Auth::user()->phone }}" id="phone" placeholder="Telefone:" required>
                            <label for="phone">Telefone:</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-4 offset-md-8 col-lg-4 offset-lg-8">
                        <button type="submit" class="btn btn-dark w-100">Atualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('change-photo-button').addEventListener('click', function() {
            document.getElementById('photo-input').click();
        });
    </script>
@endsection