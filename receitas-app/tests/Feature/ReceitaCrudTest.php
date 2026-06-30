<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReceitaCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_receitas_page(): void
    {
        $this->post('/register', [
            'nome' => 'Ana',
            'login' => 'ana',
            'senha' => 'senha123',
            'senha_confirmation' => 'senha123',
        ]);

        $response = $this->get('/receitas');

        $response->assertStatus(200);
        $response->assertSee('Receitas');
    }

    public function test_can_create_receita(): void
    {
        $this->post('/register', [
            'nome' => 'Ana',
            'login' => 'ana',
            'senha' => 'senha123',
            'senha_confirmation' => 'senha123',
        ]);

        $response = $this->post('/receitas', [
            'nome' => 'Pudim',
            'descricao' => 'Receita de pudim simples',
            'data_registro' => '2026-06-23',
            'custo' => '15.50',
            'tipo_receita' => 'doce',
        ]);

        $response->assertRedirect('/receitas');
        $this->assertDatabaseHas('receitas', [
            'nome' => 'Pudim',
            'tipo_receita' => 'doce',
        ]);
    }
}
