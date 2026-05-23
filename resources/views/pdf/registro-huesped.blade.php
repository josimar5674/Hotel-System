<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">

    <style>

        body{
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        .titulo{
            text-align:center;
            font-size:28px;
            font-weight:bold;
            margin-bottom:20px;
        }

        .subtitulo{
            text-align:center;
            font-size:18px;
            margin-bottom:20px;
        }

        .box{
            border:1px solid #000;
            padding:15px;
            margin-bottom:20px;
        }

        .section-title{
            font-size:18px;
            font-weight:bold;
            margin-bottom:10px;
        }

        .line{
            margin-bottom:8px;
        }

        .firma{
            margin-top:60px;
            text-align:center;
        }

        .footer{
            margin-top:10px;
            font-size:8px;
            text-align:justify;
        }

        .page-break{
            page-break-after: always;
        }

    </style>

</head>

<body>


@foreach($reserva->huespedes as $huesped)

    <div class="titulo">

        Parte de Entrada de Viajeros

    </div>

    <div class="subtitulo">

        Hoja de Registro Huésped

    </div>


    {{-- DATOS ESTABLECIMIENTO --}}

    <div class="box">

        <table width="100%">

            <tr>

                <td width="70%" valign="top">

                    <div class="section-title">

                        Datos Establecimiento

                    </div>

                    <div class="line">

                        <strong>R.T.N:</strong>

                        {{ $config->rtn }}

                    </div>

                    <div class="line">

                        <strong>Nombre:</strong>

                        {{ $config->nombre_negocio }}

                    </div>

                    <div class="line">

                        <strong>Dirección:</strong>

                        {{ $config->direccion }}

                    </div>

                    <div class="line">

                        <strong>Teléfono:</strong>

                        {{ $config->telefono }}

                    </div>

                    <div class="line">

                        <strong>Correo:</strong>

                        {{ $config->correo }}

                    </div>

                </td>


                {{-- LOGO --}}

                <td width="30%" align="center" valign="middle">

                    @if($config->logo)

                        <img src="{{ public_path(
                            'storage/' . $config->logo
                        ) }}"
                        style="
                            max-width:120px;
                            max-height:120px;
                            object-fit:contain;
                        ">

                    @endif

                </td>

            </tr>

        </table>

    </div>


    {{-- DATOS VIAJERO --}}

    <div class="box">

        <div class="section-title">

            Datos Viajero

        </div>

        <div class="line">

            <strong>ID / Pasaporte:</strong>

            {{ $huesped->identidad }}

        </div>

        <div class="line">

            <strong>Nombre:</strong>

            {{ $huesped->nombre }}

        </div>
        <div class="line">

    <strong>Teléfono:</strong>

    {{ $huesped->telefono ?? 'N/A' }}

</div>

<div class="line">

    <strong>Correo Electrónico:</strong>

    {{ $huesped->correo ?? 'N/A' }}

</div>

<div class="line">

    <strong>Dirección:</strong>

    {{ $huesped->direccion ?? 'N/A' }}

</div>

        <div class="line">

            <strong>Nacionalidad:</strong>

            {{ $huesped->nacionalidad }}

        </div>

        <div class="line">

            <strong>País Procedencia:</strong>

            {{ $huesped->pais_procedencia }}

        </div>

        <div class="line">

            <strong>Género:</strong>

            {{ $huesped->genero }}

        </div>

        <div class="line">

            <strong>Fecha Nacimiento:</strong>

            {{ $huesped->fecha_nacimiento }}

        </div>

    </div>


    {{-- DATOS ESTANCIA --}}

    <div class="box">

        <div class="section-title">

            Datos Estancia

        </div>

        <div class="line">

            <strong>Habitación:</strong>

            {{ $reserva->habitacion->numero }}

        </div>

        <div class="line">

            <strong>Tipo Habitación:</strong>

            {{ $reserva->habitacion->tipo }}

        </div>

        <div class="line">

            <strong>N° Ocupantes:</strong>

            {{ $reserva->cantidad_personas }}

        </div>

        <div class="line">

            <strong>Fecha Entrada:</strong>

            {{ $reserva->fecha_entrada }}

        </div>

        <div class="line">

            <strong>Fecha Salida:</strong>

            {{ $reserva->fecha_salida }}

        </div>

    </div>


    {{-- FIRMA --}}

    <div class="firma">

        ____________________________________

        <br><br>

        Firma del Ocupante / Guest Signature

    </div>


    {{-- FOOTER --}}

    <div class="footer">

        Todos los datos facilitados serán tratados de forma confidencial,
        incorporándose a nuestro fichero automatizado de clientes y se
        destinarán a ofrecerle información sobre las diferentes ofertas y
        acciones del establecimiento.

        <br><br>

        {{ $config->rtn }}
        —
        {{ $config->nombre_negocio }}
        —
        {{ $config->direccion }}
        —
        {{ $config->telefono }}

    </div>


    {{-- SALTO DE PÁGINA --}}

    @if(!$loop->last)

        <div class="page-break"></div>

    @endif


@endforeach

</body>

</html>