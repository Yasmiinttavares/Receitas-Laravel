<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Novo Usuário</title>
</head>
<body>
    <h1>Novo Usuário</h1>
    <form action="{{ route('usuarios.store') }}" method="POST">
        @csrf
        <p><label>Nome: <input type="text" name="nome" required></label></p>
        <p><label>Login: <input type="text" name="login" required></label></p>
        <p><label>Senha: <input type="password" name="senha" required></label></p>
        <p><label>Situação: <select name="situacao">
            <option value="1">Ativo</option>
            <option value="0">Inativo</option>
        </select></label></p>
        <button type="submit">Salvar</button>
        <a href="{{ route('usuarios.index') }}">Voltar</a>
    </form>
</body>
</html>
