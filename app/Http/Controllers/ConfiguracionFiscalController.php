<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConfiguracionFiscal;
use App\Models\FormaPago;
use App\Models\Extra;


class ConfiguracionFiscalController extends Controller
{
    public function edit()
    {
        $config = ConfiguracionFiscal::first();

        if (!$config) {

            $config = ConfiguracionFiscal::create([
                'nombre_negocio' => '',
                'rtn' => '',
                'direccion' => '',
                'telefono' => '',
                'cai' => '',
                'factura_inicio' => '',
                'factura_fin' => '',
                'siguiente_numero' => '',
                'fecha_limite' => now(),
                
            ]);
        }

        $formasPago = FormaPago::all();
        $extras = Extra::all();

       return view(
    'configuracion-fiscal.edit',
    compact(
        'config',
        'formasPago',
        'extras'
    )
);
    }


    public function update(Request $request)
    {
        $config = ConfiguracionFiscal::first();

        $config->update([

            'nombre_negocio' => $request->nombre_negocio,

            'razon_social' => $request->razon_social,

            'rtn' => $request->rtn,

            'direccion' => $request->direccion,

            'telefono' => $request->telefono,

            'correo' => $request->correo,

            'cai' => $request->cai,

            'factura_inicio' => $request->factura_inicio,

            'factura_fin' => $request->factura_fin,

            'siguiente_numero' => $request->siguiente_numero,

            'fecha_limite' => $request->fecha_limite,

            'impuesto_15' => $request->impuesto_15,

            'impuesto_18' => $request->impuesto_18,

            'impuesto_turismo' => $request->impuesto_turismo,
            'serie' => $request->serie,
        ]);

        return back()->with(
            'success',
            'Configuración fiscal actualizada'
        );
    }
}