<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <form action="{{ url('/login') }}" method="POST">
        @csrf
        <p><label>Login: <input type="text" name="login" required></label></p>
        <p><label>Senha: <input type="password" name="senha" required></label></p>
        <button type="submit">Entrar</button>
    </form>
    <p><a href="{{ url('/register') }}">Criar conta</a></p>
</body>
</html>
