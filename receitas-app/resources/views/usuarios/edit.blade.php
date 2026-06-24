<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Usuário</title>
</head>
<body>
    <h1>Editar Usuário</h1>
    <form action="{{ route('usuarios.update', $usuario) }}" method="POST">
        @csrf
        @method('PUT')
        <p><label>Nome: <input type="text" name="nome" value="{{ $usuario->nome }}" required></label></p>
        <p><label>Login: <input type="text" name="login" value="{{ $usuario->login }}" required></label></p>
        <p><label>Senha: <input type="password" name="senha"></label></p>
        <p><label>Situação: <select name="situacao">
            <option value="1" @selected($usuario->situacao)>Ativo</option>
            <option value="0" @selected(!$usuario->situacao)>Inativo</option>
        </select></label></p>
        <button type="submit">Atualizar</button>
        <a href="{{ route('usuarios.index') }}">Voltar</a>
    </form>
</body>
</html>
