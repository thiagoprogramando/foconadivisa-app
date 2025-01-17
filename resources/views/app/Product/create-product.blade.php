@extends('app.layout')
@section('title') Crie seu Produto @endsection
@section('content')

    <div class="col-sm-12 col-md-12 col-lg-12 card mb-3 p-5">
        <div class="row g-0">

            <div class="col-12 mb-3">
                <div class="btn-group" role="group">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#newPlan" class="btn btn-dark modal-swal">Novo Produto</button>
                    <a href="{{ route('produtos') }}" title="Recarregar" class="btn btn-outline-dark"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>
            </div>

            <div class="col-12">
                <form action="{{ route('create-product') }}" method="POST" class="row" enctype="multipart/form-data">
                    @csrf
                    <div class="col-12 col-sm-12 col-md-7 col-lg-7">
                        <div class="form-floating mb-2">
                            <input type="text" name="name" class="form-control" id="name" placeholder="Dê um nome ao Produto:" required>
                            <label for="name">Dê um nome ao Produto:</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-5 col-lg-5">
                        <div class="form-floating mb-2">
                            <input type="file" name="photo" class="form-control" id="photo" placeholder="Arquivo" required>
                            <label for="photo">Capa do Produto: <span class="badge bg-danger">Recomendado: 1080 x 1080</span></label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-3">
                        <textarea name="description" class="tinymce-editor" id="description" placeholder="Dê uma descrição ao Produto:"></textarea>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-floating mb-2">
                            <input type="text" name="value" class="form-control" id="value" placeholder="Valor:">
                            <label for="value">Valor:</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-floating mb-2">
                            <select class="form-select" name="status" id="floatingSelect" aria-label="Floating label select example">
                                <option selected="0">Status</option>
                                <option value="0">Pendente (Rascunho)</option>
                                <option value="1">Aprovado (Disponível)</option>
                            </select>
                            <label for="floatingSelect">Status do Produto</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-floating mb-2">
                            <input type="file" name="file" class="form-control" id="file" placeholder="Arquivo" required>
                            <label for="file">Arquivo <span class="badge bg-danger">Máximo 25MB</span></label>
                        </div>
                    </div>

                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <p><a href="">Métodos de Pagamento</a></p>
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="credit_card" id="flexSwitchCheckChecked">
                                    <label class="form-check-label" for="flexSwitchCheckChecked">Cartão de crédito</label>
                                </div>
                                <div class="form-floating mb-2">
                                    <input type="number" name="installments_credit" class="form-control" id="installments_credit" placeholder="Parcelas">
                                    <label for="installments_credit">Parcelas</label>
                                </div>
                            </div>

                            <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="pix" id="flexSwitchCheckChecked" checked>
                                    <label class="form-check-label" for="flexSwitchCheckChecked">Pix</label>
                                </div>
                                <div class="form-floating mb-2">
                                    <input type="number" name="installments_pix" value="1" class="form-control" id="installments_pix" placeholder="Parcelas" disabled>
                                    <label for="installments_pix">Parcelas</label>
                                </div>
                            </div>

                            <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="boleto" id="flexSwitchCheckChecked">
                                    <label class="form-check-label" for="flexSwitchCheckChecked">Boleto</label>
                                </div>
                                <div class="form-floating mb-2">
                                    <input type="number" name="installments_boleto" class="form-control" id="installments_boleto" placeholder="Parcelas">
                                    <label for="installments_boleto">Parcelas</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-12 offset-md-3 col-md-6 offset-lg-3 col-lg-6 btn-group">
                        <a href="{{ route('produtos') }}" class="btn btn-outline-danger">Fechar</a>
                        <button type="submit" class="btn btn-dark">Criar Produto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection