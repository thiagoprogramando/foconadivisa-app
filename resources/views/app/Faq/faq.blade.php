@extends('app.layout')
@section('title') FAQ - Perguntas frequentes @endsection
@section('content')

    <div class="row">
        <div class="col-12 card p-5">
            <div class="text-center">
                <h3 class="display-6">FAQ</h3>
                <p class="lead">Perguntas frequentes</p>
            </div>

            <form action="{{ route('faq') }}" method="GET" class="row">
                <div class="col-10 col-sm-10 offset-md-2 col-md-6 offset-lg-2 col-lg-6 mb-3">
                    <input type="search" name="search" class="form-control" id="search" placeholder="Pesquisar...">
                </div>
                <div class="col-2 col-sm-2 col-md-2 col-lg-2 mb-3">
                    <button type="submit" title="pesquisar" class="btn btn-dark"><i class="bi bi-search"></i></button>
                </div>
            </form>

            <div class="accordion accordion-flush" id="faq-group-2">

                @if(Auth::user()->type == 1 || Auth::user()->type == 2)
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" data-bs-target="#faqsTwo-1" type="button" data-bs-toggle="collapse" aria-expanded="false">Cadastrar FAQ</button>
                        </h2>
                        <div id="faqsTwo-1" class="accordion-collapse collapse" data-bs-parent="#faq-group-2" style="">
                            <div class="accordion-body">
                                <form action="{{ route('create-faq') }}" method="POST" class="row">
                                    @csrf
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                        <div class="form-floating mb-2">
                                            <input type="text" name="title" class="form-control" id="name" placeholder="Nome:" required>
                                            <label for="name">Nome</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                                        <div class="form-floating mb-3">
                                            <select name="type" class="form-select" id="typeSelect">
                                            <option selected="">Categorias</option>
                                            <option value="1">Padrão</option>
                                            <option value="2">Financeiro</option>
                                            <option value="3">Produtos</option>
                                            <option value="4">Questões</option>
                                            <option value="5">Respostas</option>
                                            <option value="6">Cadernos</option>
                                            </select>
                                            <label for="typeSelect">Escolha uma categoria</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                                        <div class="form-floating mb-3">
                                            <select name="plan_id" class="form-select" id="planSelect">
                                            <option selected="">Planos</option>
                                            <option value="">Não associar</option>
                                            @foreach ($plans as $plan)
                                                <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                                            @endforeach
                                            </select>
                                            <label for="planSelect">Associar plano (Opcional)</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-floating mb-3">
                                            <textarea name="response" class="form-control" placeholder="Resposta" id="floatingTextarea" style="height: 100px;"></textarea>
                                            <label for="floatingTextarea">Resposta</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 offset-md-8 col-md-4 offset-lg-8 col-lg-4 d-grid gap-2">
                                        <button type="submit" class="btn btn-dark">Cadastrar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                @foreach ($faqs as $faq)
                    <div class="accordion-item">
                        <h2 class="accordion-header"> <button class="accordion-button collapsed" data-bs-target="#faqs{{ $faq->id }}" type="button" data-bs-toggle="collapse">{{ $faq->title }}</button></h2>
                        <div id="faqs{{ $faq->id }}" class="accordion-collapse collapse" data-bs-parent="#faq-group-2">
                            <div class="accordion-body">
                                {!! $faq->response !!}
                                <hr>
                                <span class="badge bg-dark rounded-pill">{{ $faq->typeLabel() }}</span>
                                @if(Auth::user()->type == 1 || Auth::user()->type == 2)
                                    <a href="{{ route('delete-faq', ['id' => $faq->id]) }}"><span class="badge bg-danger rounded-pill"><i class="bi bi-trash"></i></span></a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <a href="{{ env('URL_SUPORT') }}" class="floating-button">
        <i class="bx bxs-help-circle"></i>
        Fale conosco
    </a>
@endsection