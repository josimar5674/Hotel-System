<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">

<style>

   @page {
    size: letter;
    margin: 30px;
}

body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 12px;
    color: #222;
}

.watermark {

    position: fixed;

    top: 20%;

    left: 10%;

    width: 80%;

    text-align: center;

    opacity: 0.06;

    z-index: -1;
}

.watermark img {

    width: 450px;
}

.header-table {

    width: 100%;
    margin-bottom: 20px;
}

.logo {

    width: 120px;
}

.empresa {

    text-align: center;
}

.empresa h2 {

    margin: 0;
    font-size: 22px;
}

.empresa p {

    margin: 2px 0;
}

.box {

    border: 1px solid #ccc;

    padding: 10px;

    margin-bottom: 15px;

    border-radius: 4px;
}

.detalle {

    width: 100%;

    border-collapse: collapse;

    margin-top: 15px;
}

.detalle th {

    background: #f2f2f2;

    border: 1px solid #ccc;

    padding: 8px;
}

.detalle td {

    border: 1px solid #ccc;

    padding: 8px;
}

.right {

    text-align: right;
}

.totales {

    width: 40%;

    margin-left: auto;

    margin-top: 20px;
}

.totales td {

    padding: 6px;
}

.total-final {

    font-size: 16px;

    font-weight: bold;

    border-top: 2px solid #000;
}

.seccion-titulo {

    background: #f5f5f5;

    padding: 6px;

    font-weight: bold;

    border: 1px solid #ddd;

    margin-top: 15px;
}

</style>

<body>

@if($config->logo)

<div class="watermark">

    <img src="{{ public_path('storage/' . $config->logo) }}">

</div>

@endif


<table class="header-table">

    <tr>

        <td width="20%">

            @if($config->logo)

                <img
                    src="{{ public_path('storage/' . $config->logo) }}"
                    class="logo">

            @endif

        </td>

        <td width="80%" class="empresa">

            <h2>{{ $config->nombre_negocio }}</h2>

            <p>{{ $config->razon_social }}</p>

            <p>{{ $config->direccion }}</p>

            <p>RTN: {{ $config->rtn }}</p>

            <p>Correo: {{ $config->correo }}</p>

            <p>Teléfono: {{ $config->telefono }}</p>

        </td>

    </tr>

</table>


<h2 style="text-align:center; margin-bottom:15px;">
    FACTURA DE VENTA
</h2>


<div class="box">

    <table width="100%">

        <tr>

            <td>
                <strong>Factura:</strong>
                {{ $factura->numero_factura }}
            </td>

            <td>
                <strong>Serie:</strong>
                {{ $config->serie }}/{{ substr($factura->numero_factura, -4) }}
            </td>

        </tr>

        <tr>

            <td>
                <strong>Fecha:</strong>
                {{ \Carbon\Carbon::parse($factura->fecha_emision)->format('d/m/Y') }}
            </td>

            <td>
                <strong>Hora:</strong>
                {{ \Carbon\Carbon::parse($factura->fecha_emision)->format('h:i A') }}
            </td>

        </tr>

        <tr>

            <td colspan="2">

                <strong>CAI:</strong>

                {{ $factura->cai }}

            </td>

        </tr>

        <tr>

            <td colspan="2">

                <strong>Fecha límite de emisión:</strong>

                {{ $factura->fecha_limite }}

            </td>

        </tr>

    </table>

</div>


<div class="box">

    <table width="100%">

        <tr>

            <td width="20%">
                <strong>Cliente:</strong>
            </td>

            <td>
                {{ $factura->cliente->nombre }}
            </td>

        </tr>

        <tr>

            <td>
                <strong>RTN:</strong>
            </td>

            <td>
                {{ $factura->cliente->rtn }}
            </td>

        </tr>

    </table>

</div>

@php

$entrada = \Carbon\Carbon::parse(
    $factura->reserva->fecha_entrada
);

$salida = \Carbon\Carbon::parse(
    $factura->reserva->fecha_salida
);

$noches = $entrada->diffInDays($salida);

$subtotalHabitacion = (
    $noches *
    $factura->reserva->habitacion->precio
);

$totalDescuentosHabitacion = DB::table(
    'reserva_huespedes'
)
->where(
    'reserva_id',
    $factura->reserva->id
)
->sum('descuento_monto');

$totalDescuentoFinal = (
    $totalDescuentosHabitacion *
    $noches
);

$totalHospedaje = (
    $subtotalHabitacion -
    $totalDescuentoFinal
);

@endphp


<table class="detalle">

    <thead>

        <tr>

            <th align="left">
                Descripción
            </th>

            <th width="80">
                Cantidad
            </th>

            <th width="120">
                Total
            </th>

        </tr>

    </thead>

    <tbody>

        <tr>

            <td>

                Hospedaje Habitación

                {{ $factura->reserva->habitacion->numero }}

            </td>

            <td align="center">
                1
            </td>

            <td align="right">

             L. {{ number_format($totalHospedaje, 2) }}

            </td>

        </tr>


        @foreach($factura->reserva->extras as $extra)

        @php

            $subtotalExtra = (
                $extra->pivot->cantidad *
                $extra->pivot->precio
            );

            $descuentoExtra = (
                ($extra->pivot->descuento_monto ?? 0)
                *
                $extra->pivot->cantidad
            );

            $totalExtra = (
                $subtotalExtra -
                $descuentoExtra
            );

        @endphp

        <tr>

            <td>

                Extra:

                {{ $extra->nombre }}

            </td>

            <td align="center">

                {{ $extra->pivot->cantidad }}

            </td>

            <td align="right">

                L. {{ number_format(
                    $totalExtra,
                    2
                ) }}

            </td>

        </tr>

        @endforeach


        @php

            $descuentos = DB::table(
                'reserva_huespedes'
            )
            ->where(
                'reserva_id',
                $factura->reserva->id
            )
            ->sum('descuento_monto');

        @endphp


        @if($descuentos > 0)

        <tr>

            <td colspan="2">

                Descuentos aplicados

            </td>

            <td align="right">

                - L. {{ number_format(
                    $descuentos,
                    2
                ) }}

            </td>

        </tr>

        @endif

    </tbody>

</table>


<div class="seccion-titulo">

    MÉTODOS DE PAGO

</div>

<table class="detalle">

    @foreach($factura->pagos as $pago)

    <tr>

        <td>

            {{ $pago->formaPago->nombre }}

        </td>

        <td align="right">

            L. {{ number_format(
                $pago->monto,
                2
            ) }}

        </td>

    </tr>

    @endforeach

</table>


<table class="totales">

    <tr>

        <td>
            Subtotal
        </td>

        <td align="right">

            L. {{ number_format(
                $factura->subtotal,
                2
            ) }}

        </td>

    </tr>

    <tr>

        <td>
            ISV 15%
        </td>

        <td align="right">

            L. {{ number_format(
                $factura->impuesto_15,
                2
            ) }}

        </td>

    </tr>

    <tr>

        <td>
            Impuesto Turismo
        </td>

        <td align="right">

            L. {{ number_format(
                $factura->impuesto_turismo,
                2
            ) }}

        </td>

    </tr>

    <tr class="total-final">

        <td>
            TOTAL
        </td>

        <td align="right">

            L. {{ number_format(
                $factura->total,
                2
            ) }}

        </td>

    </tr>

</table>

</body>