<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\FormaPago;
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

        $facturas = Factura::query()
            ->where('estado', 'emitida')
            ->whereBetween('fecha_emision', [
                Carbon::parse($fechaDesde)->startOfDay(),
                Carbon::parse($fechaHasta)->endOfDay(),
            ]);

        $totalFacturado = (clone $facturas)->sum('total');

        $totalImpuestos = (clone $facturas)->sum('total_impuestos');

        $cantidadFacturas = (clone $facturas)->count();


   $detalleFacturacion = (clone $facturas)
    ->with([
        'cliente',
        'reserva',
    ])
    ->orderBy('fecha_emision', 'desc')
    ->paginate(10)
    ->withQueryString();

            $facturacionPorDia = (clone $facturas)
    ->selectRaw('
        DATE(fecha_emision) as fecha,
        COUNT(*) as cantidad,
        SUM(subtotal) as subtotal,
        SUM(total_impuestos) as impuestos,
        SUM(total) as total
    ')
    ->groupBy('fecha')
    ->orderBy('fecha')
    ->get();

$facturacionPorFormaPago = (clone $facturas)
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


    $distribucionImpuestos = [
    [
        'tipo' => 'Impuesto 15%',
        'total' => (clone $facturas)->sum('impuesto_15'),
    ],
    [
        'tipo' => 'Impuesto 18%',
        'total' => (clone $facturas)->sum('impuesto_18'),
    ],
    [
        'tipo' => 'Impuesto turismo',
        'total' => (clone $facturas)->sum('impuesto_turismo'),
    ],
];


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