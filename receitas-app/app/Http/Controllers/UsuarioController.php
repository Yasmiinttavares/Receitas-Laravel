<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::latest()->get();

        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'usuario' => ['required', 'string', 'max:100', 'unique:users,usuario'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $data['password'] = bcrypt($data['password']);

        User::create($data);

        return redirect()->route('login')->with('success', 'Usuário cadastrado com sucesso!');
    }

    public function edit(User $usuario)
    {
        return view('usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, User $usuario)
    {
        $data = $request->validate([
            'usuario' => ['required', 'string', 'max:100', 'unique:users,usuario,'.$usuario->id],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        if (! empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $usuario->update($data);

        return redirect()->route('login')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(User $usuario)
    {
        $usuario->delete();

        return redirect()->route('login')->with('success', 'Usuário removido com sucesso!');
    }
}
