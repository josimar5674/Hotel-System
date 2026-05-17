<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = Cliente::paginate(20);

        return view(
            'clientes.index',
            compact('clientes')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([

            'nombre' => 'required',

            'identidad' => 'required|unique:clientes,identidad',

            'telefono' => 'required',

        ], [

            'nombre.required' =>
                'El nombre es obligatorio.',

            'identidad.required' =>
                'La identidad es obligatoria.',

            'identidad.unique' =>
                'La identidad ya existe.',

            'telefono.required' =>
                'El teléfono es obligatorio.',

        ]);


        Cliente::create([

            'nombre' => $request->nombre,

            'identidad' => $request->identidad,

            'rtn' => $request->rtn,

            'telefono' => $request->telefono,

            'correo' => $request->correo,

            'direccion' => $request->direccion,

        ]);


        return redirect()

            ->route('clientes.index')

            ->with(
                'success',
                'Cliente creado correctamente'
            );
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        return view(
            'clientes.edit',
            compact('cliente')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
        Cliente $cliente
    ) {

        $request->validate([

            'nombre' => 'required',

            'identidad' =>
                'required|unique:clientes,identidad,' . $cliente->id,

            'telefono' => 'required',

        ], [

            'nombre.required' =>
                'El nombre es obligatorio.',

            'identidad.required' =>
                'La identidad es obligatoria.',

            'identidad.unique' =>
                'La identidad ya existe.',

            'telefono.required' =>
                'El teléfono es obligatorio.',

        ]);


        $cliente->update([

            'nombre' => $request->nombre,

            'identidad' => $request->identidad,

            'rtn' => $request->rtn,

            'telefono' => $request->telefono,

            'correo' => $request->correo,

            'direccion' => $request->direccion,

        ]);


        return redirect()

            ->route('clientes.index')

            ->with(
                'success',
                'Cliente actualizado'
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente)
    {
        //
    }
}