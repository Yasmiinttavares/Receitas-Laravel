<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastro</title>
</head>
<body>
    <h1>Cadastro</h1>
    <form action="{{ url('/register') }}" method="POST">
        @csrf
        <p><label>Nome: <input type="text" name="nome" required></label></p>
        <p><label>Login: <input type="text" name="login" required></label></p>
        <p><label>Senha: <input type="password" name="senha" required></label></p>
        <p><label>Confirmar senha: <input type="password" name="senha_confirmation" required></label></p>
        <input type="hidden" name="situacao" value="1">
        <button type="submit">Cadastrar</button>
    </form>
    <p><a href="{{ url('/login') }}">Já tenho conta</a></p>
</body>
</html>
