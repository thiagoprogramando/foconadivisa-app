<h2>Olá, {{ $data['toName'] }}</h2>
<hr>
<p>Você solicitou recuperação para a seguinte conta:</p>
<small>Site: {{ env('APP_URL') }}</small> <br>
<small>Email: {{ $data['toEmail'] }}</small> <br>
<small>Nome: {{ $data['toName'] }}</small> <br>

<p>Para redefir sua senha, acesse o link abaixo e forneça novos dados:</p>
<a href="{{ env('APP_URL') }}recuperar-conta/{{ $data['message'] }}">Redefinição de senha</a>