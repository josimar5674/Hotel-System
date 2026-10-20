<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConfiguracionFiscal;
use App\Models\FormaPago;
use App\Models\Extra;
use App\Models\Descuento;

class ConfiguracionFiscalController extends Controller
{
    public function edit()
    {
        $config = ConfiguracionFiscal::first();

        if (!$config) {
            $config = ConfiguracionFiscal::create([
                'nombre_negocio' => '',
                'razon_social' => '',
                'rtn' => '',
                'direccion' => '',
                'telefono' => '',
                'correo' => '',
                'cai' => '',
                'rango_autorizado' => '',
                'serie' => '',
                'factura_inicio' => null,
                'factura_fin' => null,
                'siguiente_numero' => '',
                'fecha_limite' => now()->toDateString(),
            ]);
        }

        $formasPago = FormaPago::all();
        $extras = Extra::all();
        $descuentos = Descuento::all();

        return view(
            'configuracion-fiscal.edit',
            compact(
                'config',
                'formasPago',
                'extras',
                'descuentos'
            )
        );
    }

    public function update(Request $request)
    {
        $request->validate([
            'rango_autorizado' => ['nullable', 'string', 'max:255'],
        ]);

        $config = ConfiguracionFiscal::first();

        if (!$config) {
            $config = ConfiguracionFiscal::create([]);
        }

        $config->update([
            'nombre_negocio' => $request->nombre_negocio,
            'razon_social' => $request->razon_social,
            'rtn' => $request->rtn,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
            'cai' => $request->cai,
            'rango_autorizado' => $request->rango_autorizado,
            'factura_inicio' => $request->factura_inicio,
            'factura_fin' => $request->factura_fin,
            'siguiente_numero' => $request->siguiente_numero,
            'fecha_limite' => $request->fecha_limite,
            'impuesto_15' => $request->impuesto_15,
            'impuesto_18' => $request->impuesto_18,
            'impuesto_turismo' => $request->impuesto_turismo,
            'serie' => $request->serie,
        ]);

        // SUBIR LOGO
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo')->store('logos', 'public');

            $config->logo = $logo;
            $config->save();
        }

        return back()->with(
            'success',
            'Configuración fiscal actualizada'
        );
    }
}