<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">

<style>

    @page {

        margin: 10px;

    }

    body {

        font-family: monospace;

        font-size: 11px;

width: 100%;

        color: #000;

    }

    h2, h3, p {

        margin: 2px 0;

    }

    .center {

        text-align: center;

    }

    .mb {

        margin-bottom: 10px;

    }

    .linea {

        border-top: 1px dashed #000;

        margin: 6px 0;

    }

    table {

        width: 100%;

        border-collapse: collapse;

    }

    td, th {

        padding: 2px;

        vertical-align: top;

    }

    th {

        border-bottom: 1px dashed #000;

        padding-bottom: 4px;

    }

    .right {

        text-align: right;

    }

    .bold {

        font-weight: bold;

    }

    .total-final {

        font-size: 14px;

        font-weight: bold;

    }

    @page {
        margin: 10px;
    }

    body {

        font-family: monospace;

        font-size: 11px;

width: 100%;
    }

    .center {
        text-align: center;
    }

    .mb {
        margin-bottom: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    td, th {
        padding: 3px;
        vertical-align: top;
    }

    .linea {
        border-top: 1px dashed #000;
        margin: 5px 0;
    }

</style>

<body>

    <div class="center mb">

        <h2>{{ $config->nombre_negocio }}</h2>


        <p>
            {{ $config->razon_social }}
        </p>

        <p>
            {{ $config->direccion }}
        </p>


        <p>
            RTN: {{ $config->rtn }}
        </p>
 <p>
            Correo: {{ $config->correo }}
        </p>
        

        <p>
            Teléfono: {{ $config->telefono }}
        </p>

    </div>
   <h3>FACTURA DE VENTA</h3>

    <table class="mb">

     
        <tr>
    <td><strong>Fecha:</strong></td>

    <td>
        {{ \Carbon\Carbon::parse($factura->fecha_emision)->format('d/m/Y') }}
    </td>
</tr>

<tr>
    <td><strong>Hora:</strong></td>

    <td>
        {{ \Carbon\Carbon::parse($factura->fecha_emision)->format('h:i A') }}
    </td>
</tr>


        <tr>
            <td><strong>CAI:</strong></td>
            <td>{{ $factura->cai }}</td>
        </tr>

        <tr>
            <td><strong>Factura:</strong></td>
            <td>{{ $factura->numero_factura }}</td>
        </tr>

         <tr>
            <td><strong>Serie:</strong></td>
            <td>{{ $config->serie }}/{{ substr($factura->numero_factura, -4) }}</td>
        </tr>
        <tr>
            <td><strong>Fecha límite:</strong></td>
            <td>{{ $factura->fecha_limite }}</td>
        </tr>

    </table>


    <table class="mb">

        <tr>
            <td><strong>Cliente</strong></td>
            <td>{{ $factura->cliente->nombre }}</td>
        </tr>

        <tr>
            <td><strong>RTN Cliente</strong></td>
            <td>{{ $factura->cliente->rtn }}</td>
        </tr>

    </table>


    <table class="mb">

    <thead>

        <tr>

            <th align="left">
                Descripción
            </th>

            <th class="right">
                Cant.
            </th>

            <th class="right">
                Total
            </th>

        </tr>

    </thead>

    <tbody>

        {{-- HOSPEDAJE --}}

        <tr>

            <td>
                Hospedaje Habitación
                {{ $factura->reserva->habitacion->numero }}
            </td>

            <td class="right">
                1
            </td>

            <td class="right">
                L. {{ number_format($factura->subtotal, 2) }}
            </td>

        </tr>


        {{-- EXTRAS --}}

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

            <td class="right">
                {{ $extra->pivot->cantidad }}
            </td>

            <td class="right">
                L. {{ number_format(
                    $totalExtra,
                    2
                ) }}
            </td>

        </tr>

        @endforeach


        {{-- DESCUENTOS --}}

        @php

            $descuentos = DB::table(
                'reserva_huespedes'
            )
            ->where('reserva_id', $factura->reserva->id)
            ->sum('descuento_monto');

        @endphp


        @if($descuentos > 0)

        <tr>

            <td colspan="2">

                Descuentos aplicados

            </td>

            <td class="right">

                - L. {{ number_format(
                    $descuentos,
                    2
                ) }}

            </td>

        </tr>

        @endif

    </tbody>

</table>

<div class="linea"></div>

<p class="bold">
    MÉTODOS DE PAGO
</p>

<table class="mb">

    @foreach($factura->pagos as $pago)

    <tr>

        <td>
            {{ $pago->formaPago->nombre }}
        </td>

        <td class="right">

            L. {{ number_format(
                $pago->monto,
                2
            ) }}

        </td>

    </tr>

    @endforeach

</table>


    <table>

        <tr>
            <td><strong>Subtotal</strong></td>
            <td>
                L. {{ number_format($factura->subtotal, 2) }}
            </td>
        </tr>

        <tr>
            <td><strong>ISV 15%</strong></td>
            <td>
                L. {{ number_format($factura->impuesto_15, 2) }}
            </td>
        </tr>

        <tr>
            <td><strong>Impuesto Turismo</strong></td>
            <td>
                L. {{ number_format($factura->impuesto_turismo, 2) }}
            </td>
        </tr>

        <tr>
            <td><strong>Total</strong></td>
            <td>
                L. {{ number_format($factura->total, 2) }}
            </td>
        </tr>

    </table>

</body>

</html>