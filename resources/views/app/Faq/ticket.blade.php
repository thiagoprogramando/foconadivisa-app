@extends('app.layout')
@section('title') Tickets @endsection
@section('content')

    <div class="row">
        <div class="col-12 card p-5">
            <div class="text-center">
                <h3 class="display-6">Tickets</h3>
                <p class="lead">Relatos de problemas/dúvidas</p>
            </div>

            <form action="{{ route('tickets') }}" method="GET" class="row">
                <div class="col-10 col-sm-10 offset-md-2 col-md-6 offset-lg-2 col-lg-6 mb-3">
                    <input type="search" name="search" class="form-control" id="search" placeholder="Pesquisar...">
                </div>
                <div class="col-2 col-sm-2 col-md-2 col-lg-2 mb-3">
                    <button type="submit" title="pesquisar" class="btn btn-dark"><i class="bi bi-search"></i></button>
                </div>
            </form>

            <div class="accordion" id="faq-group-2">
                @foreach ($tickets as $ticket)
                    <div class="accordion-item">
                        <h2 class="accordion-header"> 
                            <button class="accordion-button collapsed" data-bs-target="#faqs{{ $ticket->id }}" type="button" data-bs-toggle="collapse">
                                <a href="" class="@if (!empty($ticket->response_comment)) text-success @endif">#{{ $ticket->id }}</a> - {{ $ticket->user->name }} 
                            </button>
                        </h2>
                        <div id="faqs{{ $ticket->id }}" class="accordion-collapse collapse" data-bs-parent="#faq-group-2">
                            <div class="accordion-body">
                                <p>
                                    <b>Questão</b> <br>
                                    <a href="@if (Auth::user()->type == 1) {{ route('questao', ['id' => $ticket->question->id]) }} @else # @endif" target="_blank">{!! $ticket->question->question_text !!}</a>
                                    <b>Relato</b> <br>
                                    {{ $ticket->comment }}
                                </p>
                                
                                <form action="{{ route('update-ticket') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $ticket->id }}">
                                    <textarea name="response_comment" class="form-control" rows="4" placeholder="Resposta:" @disabled(Auth::user()->type !== 1)>{{ $ticket->response_comment }}</textarea>
                                     <div class="btn-group">
                                        @if(Auth::user()->type == 1)
                                            <button type="submit" title="Enviar Resposta" class="btn btn-block btn-dark mt-2">Enviar</button>
                                            @if(Auth::user()->type == 1 || Auth::user()->type == 2)
                                                <a href="{{ route('delete-ticket', ['id' => $ticket->id]) }}" title="Excluir Ticket" class="btn btn-block btn-danger mt-2"><i class="bi bi-trash"></i></span></a>
                                            @endif
                                        @endif
                                    </div>
                                </form>
                                <hr>
                               
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection