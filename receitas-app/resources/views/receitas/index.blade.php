<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Receitas</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        table { border-collapse: collapse; width: 100%; margin-top: 1rem; }
        th, td { border: 1px solid #ccc; padding: 0.6rem; text-align: left; }
        .actions a, .actions button { margin-right: .5rem; }
    </style>
</head>
<body>
    <h1>Receitas Teste</h1>
    <p>
        <a href="{{ route('receitas.create') }}">Nova receita</a>
        |
        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit">Sair</button>
        </form>
    </p>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Tipo</th>
                <th>Data</th>
                <th>Custo</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($receitas as $receita)
                <tr>
                    <td>{{ $receita->nome }}</td>
                    <td>{{ $receita->tipo_receita }}</td>
                    <td>{{ $receita->data_registro }}</td>
                    <td>R$ {{ number_format($receita->custo, 2, ',', '.') }}</td>
                    <td class="actions">
                        <a href="{{ route('receitas.show', $receita) }}">Ver</a>
                        <a href="{{ route('receitas.edit', $receita) }}">Editar</a>
                        <form action="{{ route('receitas.destroy', $receita) }}" method="POST" style="display:inline;">
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
