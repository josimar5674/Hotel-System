<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\ConfiguracionFiscal;
use Barryvdh\DomPDF\Facade\Pdf;

class FacturaController extends Controller
{
    public function pdf(Factura $factura)
    {
        $config = ConfiguracionFiscal::first();

        $factura->load([

            'cliente',

            'reserva.habitacion',

            'reserva.extras',

            'usuario'

        ]);
$pdf = Pdf::loadView(
    'facturas.pdf',
    compact(
        'factura',
        'config'
    )
);

// TAMAÑO TICKET TÉRMICO

$pdf->setPaper(
    [0, 0, 226.77, 1200],
    'portrait'
);


        return $pdf->stream(

            'factura-' .

            $factura->numero_factura .

            '.pdf'

        );
    }
}