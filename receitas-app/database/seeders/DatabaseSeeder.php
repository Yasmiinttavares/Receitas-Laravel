<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Usuario::create([
            'nome' => 'Test User',
            'login' => 'teste',
            'senha' => Hash::make('123'),
            'situacao' => true,
        ]);
    }
}
