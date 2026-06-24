<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Receita</title>
</head>
<body>
    <h1>Editar Receita</h1>
    <form action="{{ route('receitas.update', $receita) }}" method="POST">
        @csrf
        @method('PUT')
        <p><label>Nome: <input type="text" name="nome" value="{{ $receita->nome }}" required></label></p>
        <p><label>Descrição: <textarea name="descricao">{{ $receita->descricao }}</textarea></label></p>
        <p><label>Data de registro: <input type="date" name="data_registro" value="{{ $receita->data_registro }}" required></label></p>
        <p><label>Custo: <input type="number" step="0.01" name="custo" value="{{ $receita->custo }}" required></label></p>
        <p><label>Tipo: <select name="tipo_receita">
            <option value="doce" @selected($receita->tipo_receita === 'doce')>Doce</option>
            <option value="salgada" @selected($receita->tipo_receita === 'salgada')>Salgada</option>
        </select></label></p>
        <button type="submit">Atualizar</button>
        <a href="{{ route('receitas.index') }}">Voltar</a>
    </form>
</body>
</html>
