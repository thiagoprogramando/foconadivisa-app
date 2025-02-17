@extends('layout')
@section('ecommerce')

<section class="mt-5 container">
    <div class="row mt-3">
        <div class="col-12 col-sm-12 offset-md-3 col-md-6 offset-lg-3 col-lg-6">
            <div class="card p-5">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                        <img src="{{ asset('storage/'.$product->photo) }}" class="d-block w-100" alt="{{ $product->name }}" style="object-fit: contain; max-height: 100px; width: 100px;"/>
                    </div>
                    <div class="col-12 col-sm-12 col-md-8 col-lg-8">
                        <h3><a href="">{{ $product->name }}</a></h3>
                        <small class="lead">Por apenas</small>
                        <h2><b><sup>R$</sup></b> {{ number_format($product->value, 2, ',', '.') }}</h2>
                    </div>
                </div>
                
                <hr>
                <form action="{{ route('pay-product') }}" method="POST">
                    @csrf
                    <input type="hidden" name="quanty" value="1">
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-floating mb-2">
                                <input type="text" name="name" class="form-control" id="name" placeholder="Nome completo:" required>
                                <label for="name">Nome completo:</label>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-floating mb-2">
                                <input type="email" name="email" class="form-control" id="email" placeholder="Email:" required>
                                <label for="email">Email:</label>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="form-floating mb-2">
                                <input type="text" name="phone" class="form-control" id="phone" placeholder="Celular:" required>
                                <label for="phone">Celular:</label>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="form-floating mb-2">
                                <input type="text" name="cpfcnpj" class="form-control" id="cpfcnpj" placeholder="CPF ou CNPJ:" required>
                                <label for="cpfcnpj">CPF ou CNPJ:</label>
                            </div>
                        </div>
                    </div>

                    <div class="input-group input-group-lg mb-3">
                        <select name="method" class="form-select" id="paymentMethod">
                            <option selected value="">Forma de pagamento</option>
                            @foreach($paymentMethods as $method)
                                <option value="{{ $method->method }}">{{ $method->methodLabel() }}</option>
                            @endforeach
                        </select>
                
                        <select name="installments" class="form-select" id="installments">
                            <option selected value="">Parcelas</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">PAGAR</button>
                </form>
            </div>
        </div>
    </div>
</section>
<script>
    const installmentsOptions = @json($installmentsOptions);

    document.getElementById('paymentMethod').addEventListener('change', function () {
        const paymentMethod = this.value;
        const installmentsSelect = document.getElementById('installments');
        
        installmentsSelect.innerHTML = '';
        if (installmentsOptions[paymentMethod]) {
            const maxInstallments = installmentsOptions[paymentMethod];
            
            for (let i = 1; i <= maxInstallments; i++) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = `${i}x`;
                installmentsSelect.appendChild(option);
            }
        } else {
            const option = document.createElement('option');
            option.value = 1;
            option.textContent = '1x';
            installmentsSelect.appendChild(option);
        }
    });
</script>
@endsection

    