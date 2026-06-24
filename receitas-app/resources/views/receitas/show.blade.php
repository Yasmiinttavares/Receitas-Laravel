<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $receita->nome }}</title>
</head>
<body>
    <h1>{{ $receita->nome }}</h1>
    <p><strong>Descrição:</strong> {{ $receita->descricao }}</p>
    <p><strong>Data de registro:</strong> {{ $receita->data_registro }}</p>
    <p><strong>Custo:</strong> R$ {{ number_format($receita->custo, 2, ',', '.') }}</p>
    <p><strong>Tipo:</strong> {{ $receita->tipo_receita }}</p>
    <p><a href="{{ route('receitas.index') }}">Voltar</a></p>
</body>
</html>
