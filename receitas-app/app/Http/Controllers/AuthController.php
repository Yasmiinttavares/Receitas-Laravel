<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'login' => ['required', 'string'],
            'senha' => ['required', 'string'],
        ]);

        $usuario = Usuario::where('login', $data['login'])->first();

        if ($usuario && Hash::check($data['senha'], $usuario->senha)) {
            $request->session()->put('auth_user_id', $usuario->id);

            return redirect('/receitas');
        }

        return back()->withErrors([
            'login' => 'Login ou senha inválidos.',
        ])->onlyInput('login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'login' => ['required', 'string', 'max:255', 'unique:usuarios,login'],
            'senha' => ['required', 'confirmed'],
            'situacao' => ['nullable', 'boolean'],
        ]);

        $nome = trim($request->input('nome'));
        $login = trim($request->input('login'));
        $senha = $request->input('senha');
        $situacao = $request->has('situacao') ? 1 : 1;

        $usuario = Usuario::create([
            'nome' => $nome,
            'login' => $login,
            'senha' => Hash::make($senha),
            'situacao' => $situacao,
        ]);

        $request->session()->put('auth_user_id', $usuario->id);

        return redirect('/receitas')->with('success', 'Cadastro realizado com sucesso!');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('auth_user_id');

        return redirect('/login');
    }
}
