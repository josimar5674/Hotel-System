<x-app-layout>



    <x-slot name="header">



    </x-slot>


<div class="max-w-5xl mx-auto p-6">

    <div class="flex justify-between items-center mb-6">

        <div>

            <h1 class="text-2xl font-bold">

                Detalle de Cierre Diario

            </h1>

            <p class="text-gray-500">

                Información completa del cierre realizado.

            </p>

        </div>

        <a href="{{ route('cierres.index') }}"
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">

            Volver

        </a>

    </div>


    {{-- INFORMACIÓN GENERAL --}}

    <div class="bg-white shadow rounded p-5 mb-6">

        <h2 class="font-bold text-lg mb-4">

            Información General

        </h2>

        <table class="w-full">

            <tr>

                <td class="py-2 font-semibold w-64">

                    Fecha

                </td>

                <td>

                    {{ $cierre->fecha->format('d/m/Y') }}

                </td>

            </tr>

            <tr>

                <td class="py-2 font-semibold">

                    Usuario

                </td>

                <td>

                    {{ $cierre->usuario->name }}

                </td>

            </tr>

            <tr>

                <td class="py-2 font-semibold">

                    Fecha de Cierre

                </td>

                <td>

                    {{ $cierre->fecha_cierre?->format('d/m/Y h:i A') }}

                </td>

            </tr>

        </table>

    </div>


    {{-- RESUMEN DEL DÍA --}}

    <div class="bg-white shadow rounded p-5 mb-6">

        <h2 class="font-bold text-lg mb-4">

            Resumen del Día

        </h2>

        <table class="w-full">

       <tr>
    <td class="py-2 font-semibold">
        Facturas Emitidas
    </td>
    <td class="text-right">
        {{ $cierre->cantidad_facturas }}
    </td>
</tr>

<tr>
    <td class="py-2 font-semibold">
        Subtotal
    </td>
    <td class="text-right">
        L. {{ number_format($subtotal, 2) }}
    </td>
</tr>

<tr>
    <td class="py-2 font-semibold">
        ISV 15%
    </td>
    <td class="text-right">
        L. {{ number_format($isv15, 2) }}
    </td>
</tr>

<tr>
    <td class="py-2 font-semibold">
        ISV 18%
    </td>
    <td class="text-right">
        L. {{ number_format($isv18, 2) }}
    </td>
</tr>

<tr>
    <td class="py-2 font-semibold">
        Impuesto Turismo
    </td>
    <td class="text-right">
        L. {{ number_format($turismo, 2) }}
    </td>
</tr>

<tr class="border-t">
    <td class="py-2 font-bold">
        Total Facturado
    </td>
    <td class="text-right font-bold text-green-600">
        L. {{ number_format($cierre->total_facturado, 2) }}
    </td>
</tr>

        </table>

    </div>


{{-- DETALLE DE FORMAS DE PAGO --}}

<div class="bg-white shadow rounded p-5 mb-6">

    <h2 class="font-bold text-lg mb-4">

        Formas de Pago

    </h2>

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead>

                <tr class="border-b">

                    <th class="text-left py-3">
                        Forma de Pago
                    </th>

                    <th class="text-right py-3">
                        Facturado
                    </th>

                    <th class="text-right py-3">
                        Contado
                    </th>

                    <th class="text-right py-3">
                        Diferencia
                    </th>

                </tr>

            </thead>

            <tbody>

                @foreach($cierre->detalle_pagos ?? [] as $nombre => $detalle)

                <tr class="border-b">

                    <td class="py-3">

                        {{ $nombre }}

                    </td>

                    <td class="text-right">

                        L. {{ number_format(
                            $detalle['facturado'],
                            2
                        ) }}

                    </td>

                    <td class="text-right">

                        L. {{ number_format(
                            $detalle['contado'],
                            2
                        ) }}

                    </td>

                    <td class="text-right">

                        @if($detalle['diferencia'] == 0)

                            <span class="text-green-600 font-semibold">

                                L. 0.00

                            </span>

                        @elseif($detalle['diferencia'] < 0)

                            <span class="text-red-600 font-semibold">

                                L. {{ number_format(
                                    $detalle['diferencia'],
                                    2
                                ) }}

                            </span>

                        @else

                            <span class="text-blue-600 font-semibold">

                                L. {{ number_format(
                                    $detalle['diferencia'],
                                    2
                                ) }}

                            </span>

                        @endif

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>
  

  {{-- RESUMEN DEL CIERRE --}}

<div class="bg-white shadow rounded p-5 mb-6">

    <h2 class="font-bold text-lg mb-4">

        Resultado del Cierre

    </h2>

    <table class="w-full">

        <tr>

            <td class="py-2 font-semibold">

                Total Facturado

            </td>

            <td class="text-right">

                L. {{ number_format(
                    $cierre->total_facturado,
                    2
                ) }}

            </td>

        </tr>

        <tr>

            <td class="py-2 font-semibold">

                Total Contado

            </td>

            <td class="text-right">

                L. {{ number_format(
                    $cierre->efectivo_contado,
                    2
                ) }}

            </td>

        </tr>

        <tr>

            <td class="py-2 font-semibold">

                Diferencia General

            </td>

            <td class="text-right">

                @if($cierre->diferencia == 0)

                    <span class="text-green-600 font-bold">

                        L. 0.00

                    </span>

                @elseif($cierre->diferencia < 0)

                    <span class="text-red-600 font-bold">

                        L. {{ number_format(
                            $cierre->diferencia,
                            2
                        ) }}

                    </span>

                @else

                    <span class="text-blue-600 font-bold">

                        L. {{ number_format(
                            $cierre->diferencia,
                            2
                        ) }}

                    </span>

                @endif

            </td>

        </tr>

    </table>

</div>


    {{-- OBSERVACIONES --}}

    <div class="bg-white shadow rounded p-5">

        <h2 class="font-bold text-lg mb-4">

            Observaciones

        </h2>

        @if($cierre->observaciones)

            <div class="border rounded p-4 bg-gray-50">

                {{ $cierre->observaciones }}

            </div>

        @else

            <div class="text-gray-500">

                Sin observaciones.

            </div>

        @endif

    </div>

</div>

</x-app-layout>