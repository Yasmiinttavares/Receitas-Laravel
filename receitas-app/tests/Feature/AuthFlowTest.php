<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_available(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Login');
    }

    public function test_user_can_register_and_login(): void
    {
        $registerResponse = $this->post('/register', [
            'nome' => 'Ana Silva',
            'login' => 'ana',
            'senha' => 'senha123',
            'senha_confirmation' => 'senha123',
        ]);

        $registerResponse->assertRedirect('/receitas');
        $this->assertDatabaseHas('usuarios', [
            'login' => 'ana',
        ]);
        $this->assertTrue(session()->has('auth_user_id'));

        $this->post('/logout');
        $this->assertFalse(session()->has('auth_user_id'));

        $loginResponse = $this->post('/login', [
            'login' => 'ana',
            'senha' => 'senha123',
        ]);

        $loginResponse->assertRedirect('/receitas');
        $this->assertTrue(session()->has('auth_user_id'));
    }

    public function test_seeded_test_user_can_login_with_default_password(): void
    {
        $this->seed(DatabaseSeeder::class);

        $response = $this->post('/login', [
            'login' => 'teste',
            'senha' => '123',
        ]);

        $response->assertRedirect('/receitas');
        $this->assertTrue(session()->has('auth_user_id'));
    }
}
