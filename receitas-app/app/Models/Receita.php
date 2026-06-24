<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receita extends Model
{
    protected $fillable = [
        'nome',
        'descricao',
        'data_registro',
        'custo',
        'tipo_receita',
    ];
}
