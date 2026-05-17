<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Extra;

class ExtraController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([

            'nombre' => 'required',

            'precio' => 'required|numeric|min:0'

        ]);


        Extra::create([

            'nombre' => $request->nombre,

            'precio' => $request->precio,

            'activo' => true

        ]);


        return back()->with(

            'success',

            'Extra agregado correctamente'

        );
    }


    public function update(
        Request $request,
        Extra $extra
    )
    {
        $request->validate([

            'nombre' => 'required',

            'precio' => 'required|numeric|min:0'

        ]);


        $extra->update([

            'nombre' => $request->nombre,

            'precio' => $request->precio

        ]);


        return back()->with(

            'success',

            'Extra actualizado correctamente'

        );
    }


    public function toggle(Extra $extra)
    {
        $extra->update([

            'activo' => !$extra->activo

        ]);


        return back()->with(

            'success',

            'Estado actualizado'

        );
    }
}