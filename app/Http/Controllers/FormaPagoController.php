<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormaPago;

class FormaPagoController extends Controller
{
    public function store(Request $request)
    {
        FormaPago::create([

            'nombre' => $request->nombre,

            'activo' => true

        ]);

        return back()->with(
            'success',
            'Forma de pago agregada'
        );
    }

    public function update(
    Request $request,
    FormaPago $formaPago
)
{
    $formaPago->update([

        'nombre' => $request->nombre

    ]);

    return back()->with(
        'success',
        'Forma de pago actualizada'
    );
}


public function toggle(FormaPago $formaPago)
{
    $formaPago->update([

        'activo' => !$formaPago->activo

    ]);

    return back()->with(
        'success',
        'Estado actualizado'
    );
}
}