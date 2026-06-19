<?php

namespace App\Http\Controllers;

use App\Models\CierreDiario;
use App\Models\Factura;
use App\Models\Pago;
use App\Models\FormaPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CierreDiarioController extends Controller
{
    /**
     * Obtiene el resumen de pagos por forma de pago
     */
    private function obtenerResumenPagos($fecha)
    {
        $formasPago = FormaPago::where(
            'activo',
            1
        )
            ->orderBy('nombre')
            ->get();

        $resumenPagos = [];

        foreach ($formasPago as $forma) {

            $total = Pago::where(
                'forma_pago_id',
                $forma->id
            )
                ->whereHas(
                    'factura',
                    function ($q) use ($fecha) {

                        $q->whereDate(
                            'fecha_emision',
                            $fecha
                        );
                    }
                )
                ->sum('monto');

            $resumenPagos[] = [

                'id'     => $forma->id,
                'nombre' => $forma->nombre,
                'total'  => $total,

            ];
        }

        return $resumenPagos;
    }

    /**
     * Listado
     */
    public function index()
    {
        $cierres = CierreDiario::with(
            'usuario'
        )
            ->latest('fecha')
            ->paginate(20);

        return view(
            'cierres.index',
            compact('cierres')
        );
    }

    /**
     * Formulario de cierre
     */
    public function create()
    {
       $fecha = request('fecha')
    ? Carbon::parse(
        request('fecha')
      )
    : Carbon::today();

        $existe = CierreDiario::whereDate(
            'fecha',
            $fecha
        )->exists();

        if ($existe) {

            return redirect()
                ->route('cierres.index')
                ->with(
                    'error',
                    'Ya existe un cierre para hoy'
                );
        }

        $facturas = Factura::with([
            'pagos.formaPago'
        ])
            ->whereDate(
                'fecha_emision',
                $fecha
            )
            ->get();

        $totalFacturado = $facturas->sum(
            'total'
        );

        $cantidadFacturas = $facturas->count();

        $resumenPagos = $this->obtenerResumenPagos(
            $fecha
        );

        $efectivo = collect(
            $resumenPagos
        )->first(
            fn($item) =>
            strtolower($item['nombre']) === 'efectivo'
        );

        $efectivo = $efectivo['total'] ?? 0;

        return view(
            'cierres.create',
            compact(
                'fecha',
                'totalFacturado',
                'cantidadFacturas',
                'resumenPagos',
                'efectivo'
            )
        );
    }

    /**
     * Guardar cierre
     */
public function store(Request $request)
{
    $request->validate([
            'fecha' => 'required|date',

        'conteo' => 'required|array',

        'observaciones' => 'nullable|string',

    ]);

$fecha = Carbon::parse(
    $request->fecha
);
    $existe = CierreDiario::whereDate(
        'fecha',
        $fecha
    )->exists();

    if ($existe) {

        return back()->with(
            'error',
            'Ya existe un cierre para hoy'
        );
    }

    $facturas = Factura::whereDate(
        'fecha_emision',
        $fecha
    )->get();

    $totalFacturado = $facturas->sum(
        'total'
    );

    $cantidadFacturas = $facturas->count();

    $resumenPagos = $this->obtenerResumenPagos(
        $fecha
    );

    $efectivo = collect(
        $resumenPagos
    )->first(
        fn ($item) =>
            strtolower($item['nombre']) === 'efectivo'
    );

    $efectivo = $efectivo['total'] ?? 0;

    $detallePagos = [];

    $totalContado = 0;

    $diferencia = 0;

    foreach ($resumenPagos as $forma) {

        if ($forma['total'] <= 0) {
            continue;
        }

        $contado = $request->conteo[
            $forma['id']
        ] ?? 0;

        $detallePagos[$forma['nombre']] = [

            'forma_pago_id' => $forma['id'],

            'facturado' => $forma['total'],

            'contado' => $contado,

            'diferencia' => (
                $contado -
                $forma['total']
            ),

        ];

        $totalContado += $contado;

        $diferencia += (
            $contado -
            $forma['total']
        );
    }

    CierreDiario::create([

        'fecha' => $fecha,

        'usuario_id' => Auth::id(),

        'total_facturado' =>
        $totalFacturado,

        'efectivo_facturado' =>
        $efectivo,

        'tarjeta_facturado' =>
        0,

        'transferencia_facturado' =>
        0,

        'efectivo_contado' =>
        $totalContado,

        'diferencia' =>
        $diferencia,

        'cantidad_facturas' =>
        $cantidadFacturas,

        'observaciones' =>
        $request->observaciones,

     'detalle_pagos' => $detallePagos,

        'fecha_cierre' => now(),

    ]);

    return redirect()
        ->route('cierres.index')
        ->with(
            'success',
            'Cierre realizado correctamente'
        );
}

    /**
     * Ver detalle
     */
  public function show(CierreDiario $cierre)
{
    $cierre->load(
        'usuario'
    );

    $facturas = Factura::whereDate(
        'fecha_emision',
        $cierre->fecha
    )->get();

    $subtotal = $facturas->sum(
        'subtotal'
    );

    $isv15 = $facturas->sum(
        'impuesto_15'
    );

    $isv18 = $facturas->sum(
        'impuesto_18'
    );

    $turismo = $facturas->sum(
        'impuesto_turismo'
    );

    return view(
        'cierres.show',
        compact(
            'cierre',
            'subtotal',
            'isv15',
            'isv18',
            'turismo'
        )
    );
}
}
