<?php

namespace App\Http\Controllers;

use App\Models\Descuento;
use Illuminate\Http\Request;

class DescuentoController extends Controller
{

    public function store(Request $request)
    {

        Descuento::create([

            'nombre' => $request->nombre,

            'tipo' => $request->tipo,

            'valor' => $request->valor,

            'activo' => true

        ]);

        return back()->with(
            'success',
            'Descuento agregado'
        );

    }


    public function update(
        Request $request,
        Descuento $descuento
    )
    {

        $descuento->update([

            'nombre' => $request->nombre,

            'tipo' => $request->tipo,

            'valor' => $request->valor

        ]);

        return back()->with(
            'success',
            'Descuento actualizado'
        );

    }


    public function toggle(
        Descuento $descuento
    )
    {

        $descuento->update([

            'activo' => !$descuento->activo

        ]);

        return back()->with(
            'success',
            'Estado actualizado'
        );

    }

}