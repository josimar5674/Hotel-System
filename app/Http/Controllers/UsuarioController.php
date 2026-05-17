<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::orderBy(
            'name'
        )->get();

        return view(
            'usuarios.index',
            compact('usuarios')
        );
    }


    public function create()
    {
        return view('usuarios.create');
    }


    public function store(Request $request)
    {
        $request->validate([

            'name' => 'required',

            'email' => 'required|email|unique:users',

            'password' => 'required|min:6',

            'role' => 'required'

        ]);


        User::create([

            'name' => $request->name,

            'email' => $request->email,

            'password' => Hash::make(
                $request->password
            ),

            'role' => $request->role

        ]);


        return redirect()
            ->route('usuarios.index')
            ->with(
                'success',
                'Usuario creado correctamente'
            );
    }


    public function edit(User $usuario)
    {
        return view(
            'usuarios.edit',
            compact('usuario')
        );
    }


    public function update(
        Request $request,
        User $usuario
    )
    {
        $request->validate([

            'name' => 'required',

            'email' => 'required|email|unique:users,email,' . $usuario->id,

            'role' => 'required'

        ]);


        $usuario->update([

            'name' => $request->name,

            'email' => $request->email,

            'role' => $request->role

        ]);


        // Password opcional

        if($request->password) {

            $usuario->update([

                'password' => Hash::make(
                    $request->password
                )

            ]);

        }


        return redirect()
            ->route('usuarios.index')
            ->with(
                'success',
                'Usuario actualizado'
            );
    }
}