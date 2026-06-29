<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nova Receitaaaa</title>
</head>
<body>
    <h1>Nova Receita</h1>
    <form action="{{ route('receitas.store') }}" method="POST">
        @csrf
        <p><label>Nome: <input type="text" name="nome" required></label></p>
        <p><label>Descrição: <textarea name="descricao"></textarea></label></p>
        <p><label>Data de registro: <input type="date" name="data_registro" required></label></p>
        <p><label>Custo: <input type="number" step="0.01" name="custo" required></label></p>
        <p><label>Tipo: <select name="tipo_receita">
            <option value="doce">Doce</option>
            <option value="salgada">Salgada</option>
        </select></label></p>
        <button type="submit">Salvar</button>
        <a href="{{ route('receitas.index') }}">Voltar</a>
    </form>
</body>
</html>
