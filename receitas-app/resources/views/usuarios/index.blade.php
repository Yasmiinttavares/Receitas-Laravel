<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Usuários</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        table { border-collapse: collapse; width: 100%; margin-top: 1rem; }
        th, td { border: 1px solid #ccc; padding: 0.6rem; text-align: left; }
        .actions a, .actions button { margin-right: .5rem; }
    </style>
</head>
<body>
    <h1>Usuários</h1>
    <p><a href="{{ route('usuarios.create') }}">Novo usuário</a> | <a href="{{ route('receitas.index') }}">Receitas</a></p>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Login</th>
                <th>Situação</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->nome }}</td>
                    <td>{{ $usuario->login }}</td>
                    <td>{{ $usuario->situacao ? 'Ativo' : 'Inativo' }}</td>
                    <td class="actions">
                        <a href="{{ route('usuarios.edit', $usuario) }}">Editar</a>
                        <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
