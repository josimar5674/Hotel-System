<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Exports\ReporteContableExport;
use Maatwebsite\Excel\Facades\Excel;

class ReporteContableController extends Controller
{
    public function index(Request $request)
    {
        $fechaDesde = $request->input(
            'desde',
            Carbon::now()->startOfMonth()->format('Y-m-d')
        );

        $fechaHasta = $request->input(
            'hasta',
            Carbon::now()->format('Y-m-d')
        );

        /*
        |--------------------------------------------------------------------------
        | CONSULTA BASE
        |--------------------------------------------------------------------------
        | Incluye facturas emitidas y anuladas.
        | Se utiliza para el historial y el gráfico de facturación original.
        */

        $facturas = Factura::query()
            ->whereBetween('fecha_emision', [
                Carbon::parse($fechaDesde)->startOfDay(),
                Carbon::parse($fechaHasta)->endOfDay(),
            ]);

        /*
        |--------------------------------------------------------------------------
        | FACTURAS VIGENTES
        |--------------------------------------------------------------------------
        | Solo las facturas que no han sido anuladas.
        */

        $facturasEmitidas = (clone $facturas)
            ->where('estado', 'emitida');

        /*
        |--------------------------------------------------------------------------
        | INDICADORES
        |--------------------------------------------------------------------------
        */

        $totalFacturado = (clone $facturasEmitidas)
            ->sum('total');

        $totalImpuestos = (clone $facturasEmitidas)
            ->sum('total_impuestos');

        $cantidadFacturas = (clone $facturasEmitidas)
            ->count();

            // Indicadores originales: incluyen facturas emitidas y anuladas.
            $totalFacturadoOriginal = (clone $facturas)->sum('total');

            $totalImpuestosOriginal = (clone $facturas)->sum('total_impuestos');

            $cantidadFacturasOriginal = (clone $facturas)->count();

        /*
        |--------------------------------------------------------------------------
        | DETALLE DE FACTURACIÓN
        |--------------------------------------------------------------------------
        | Incluye tanto emitidas como anuladas.
        */

        $detalleFacturacion = (clone $facturas)
            ->with([
                'cliente',
                'reserva',
            ])
            ->orderBy('fecha_emision', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | GRÁFICO: FACTURACIÓN ORIGINAL VS REAL
        |--------------------------------------------------------------------------
        | Original: todas las facturas del día, incluidas las posteriormente
        | anuladas.
        |
        | Real: únicamente las facturas que actualmente están emitidas.
        |
        | Se construye una serie por cada día del período para que ambas
        | líneas compartan las mismas fechas.
        */

        $facturacionOriginalPorDia = (clone $facturas)
            ->selectRaw('
                DATE(fecha_emision) as fecha,
                SUM(total) as total,
                COUNT(*) as cantidad
            ')
            ->groupByRaw('DATE(fecha_emision)')
            ->orderBy('fecha')
            ->get()
            ->keyBy('fecha');

        $facturacionRealPorDia = (clone $facturasEmitidas)
            ->selectRaw('
                DATE(fecha_emision) as fecha,
                SUM(total) as total,
                COUNT(*) as cantidad
            ')
            ->groupByRaw('DATE(fecha_emision)')
            ->orderBy('fecha')
            ->get()
            ->keyBy('fecha');

        $facturacionPorDia = collect();

        $inicio = Carbon::parse($fechaDesde)->startOfDay();
        $fin = Carbon::parse($fechaHasta)->startOfDay();

        for (
            $fecha = $inicio->copy();
            $fecha->lte($fin);
            $fecha->addDay()
        ) {
            $dia = $fecha->format('Y-m-d');

            $original = $facturacionOriginalPorDia->get($dia);
            $real = $facturacionRealPorDia->get($dia);

            $facturacionPorDia->push([
                'fecha' => $fecha->format('d/m/Y'),

                'original' => $original
                    ? (float) $original->total
                    : 0,

                'real' => $real
                    ? (float) $real->total
                    : 0,

                'anuladas' => max(
                    0,
                    (float) ($original->total ?? 0)
                    - (float) ($real->total ?? 0)
                ),

                'cantidad_original' => $original
                    ? (int) $original->cantidad
                    : 0,

                'cantidad_real' => $real
                    ? (int) $real->cantidad
                    : 0,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | FACTURACIÓN POR FORMA DE PAGO
        |--------------------------------------------------------------------------
        | Solo incluye pagos asociados a facturas actualmente emitidas.
        */

        $facturacionPorFormaPago = (clone $facturasEmitidas)
            ->join(
                'pagos',
                'facturas.id',
                '=',
                'pagos.factura_id'
            )
            ->join(
                'forma_pagos',
                'pagos.forma_pago_id',
                '=',
                'forma_pagos.id'
            )
            ->selectRaw('
                forma_pagos.nombre as forma_pago,
                COUNT(DISTINCT facturas.id) as cantidad,
                SUM(pagos.monto) as total
            ')
            ->groupBy(
                'forma_pagos.id',
                'forma_pagos.nombre'
            )
            ->orderByDesc('total')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | DISTRIBUCIÓN DE IMPUESTOS
        |--------------------------------------------------------------------------
        | Solo suma los impuestos de facturas actualmente emitidas.
        */

        $distribucionImpuestos = [
            [
                'tipo' => 'Impuesto 15%',
                'total' => (float) (clone $facturasEmitidas)
                    ->sum('impuesto_15'),
            ],
            [
                'tipo' => 'Impuesto 18%',
                'total' => (float) (clone $facturasEmitidas)
                    ->sum('impuesto_18'),
            ],
            [
                'tipo' => 'Impuesto turismo',
                'total' => (float) (clone $facturasEmitidas)
                    ->sum('impuesto_turismo'),
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | RESPUESTA
        |--------------------------------------------------------------------------
        */

        return view('reportes.contable', [
            'totalFacturado' => $totalFacturado,
            'totalImpuestos' => $totalImpuestos,
            'cantidadFacturas' => $cantidadFacturas,

            'fechaDesde' => $fechaDesde,
            'fechaHasta' => $fechaHasta,

            'facturacionPorDia' => $facturacionPorDia,
            'facturacionPorFormaPago' => $facturacionPorFormaPago,
            'distribucionImpuestos' => $distribucionImpuestos,

            'detalleFacturacion' => $detalleFacturacion,
            'totalFacturadoOriginal' => $totalFacturadoOriginal,
'totalImpuestosOriginal' => $totalImpuestosOriginal,
'cantidadFacturasOriginal' => $cantidadFacturasOriginal,
        ]);
    }

    public function exportExcel(Request $request)
    {
        $desde = $request->input(
            'desde',
            Carbon::now()->startOfMonth()->format('Y-m-d')
        );

        $hasta = $request->input(
            'hasta',
            Carbon::now()->format('Y-m-d')
        );

        return Excel::download(
            new ReporteContableExport($desde, $hasta),
            'reporte-contable.xlsx'
        );
    }
}