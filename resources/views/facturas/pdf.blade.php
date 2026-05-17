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

        width: 260px;

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

                <th>Descripción</th>

                <th>Cantidad</th>

                <th>Precio</th>

                <th>Total</th>

            </tr>

        </thead>

        <tbody>

            <tr>

                <td>
                    Hospedaje habitación
                    {{ $factura->reserva->habitacion->numero }}
                </td>

                <td>
                    1
                </td>

                <td>
                    L. {{ number_format($factura->subtotal, 2) }}
                </td>

                <td>
                    L. {{ number_format($factura->subtotal, 2) }}
                </td>

            </tr>

        </tbody>

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