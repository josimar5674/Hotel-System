<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">

            Extras Reserva #{{ $reserva->id }}

        </h2>

    </x-slot>


    <div class="p-6">

        <div class="bg-white shadow rounded-lg p-6">

            {{-- INFO RESERVA --}}

            <div class="mb-6">

                <h3 class="text-lg font-bold">

                    {{ $reserva->cliente->nombre }}

                </h3>

                <p>

                    Habitación:
                    {{ $reserva->habitacion->numero }}

                </p>

                <p>

                    Entrada:
                    {{ $reserva->fecha_entrada }}

                </p>

                <p>

                    Salida:
                    {{ $reserva->fecha_salida }}

                </p>

            </div>


            {{-- AGREGAR EXTRA --}}

            <form method="POST"
                  action="{{ route('reservas.extras.store', $reserva->id) }}"
                  class="mb-8">

                @csrf

                <div class="grid grid-cols-4 gap-4">

                    <div>

                        <label class="block mb-1">

                            Extra

                        </label>

                        <select name="extra_id"
                                class="w-full border rounded p-2">

                            @foreach($extras as $extra)

                                <option value="{{ $extra->id }}">

                                    {{ $extra->nombre }}
                                    -
                                    L. {{ number_format($extra->precio, 2) }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div>

    <label class="block mb-1">

        Descuento

    </label>

    <select name="descuento_id"
            class="w-full border rounded p-2">

        <option value="">
            Sin descuento
        </option>

        @foreach($descuentos as $descuento)

            <option value="{{ $descuento->id }}">

                {{ $descuento->nombre }}

                -

                @if($descuento->tipo == 'porcentaje')

                    {{ $descuento->valor }}%

                @else

                    L. {{ number_format($descuento->valor, 2) }}

                @endif

            </option>

        @endforeach

    </select>

</div>


                    <div>

                        <label class="block mb-1">

                            Cantidad

                        </label>

                        <input type="number"
                               name="cantidad"
                               min="1"
                               value="1"
                               class="w-full border rounded p-2">

                    </div>


                    <div class="flex items-end">

                        <button type="submit"
                                class="bg-purple-600 text-white px-4 py-2 rounded">

                            Agregar Extra

                        </button>

                    </div>

                </div>

            </form>


            {{-- LISTADO EXTRAS --}}

            <h3 class="text-lg font-bold mb-4">

                Extras Agregados

            </h3>


            <table class="w-full border text-sm">

    <thead>

        <tr class="bg-gray-200 text-center">

            <th class="p-3 border">
                Extra
            </th>

            <th class="p-3 border">
                Cantidad
            </th>

            <th class="p-3 border">
                Precio Unitario
            </th>

            <th class="p-3 border">
                Subtotal
            </th>

            <th class="p-3 border">
                Descuento
            </th>

            <th class="p-3 border">
                Total Final
            </th>

            <th class="p-3 border">

    Acción

</th>

        </tr>

    </thead>

    <tbody>

        @forelse($reserva->extras as $extra)

        @php

            $subtotal = (

                $extra->pivot->cantidad *

                $extra->pivot->precio

            );

            $descuento = (
                $extra->pivot->descuento_monto ?? 0
            );

            $totalFinal = (
                $subtotal - $descuento
            );

        @endphp

        <tr class="border-t text-center">

            <!-- EXTRA -->

            <td class="p-3 border">

                {{ $extra->nombre }}

            </td>


            <!-- CANTIDAD -->

            <td class="p-3 border">

                {{ $extra->pivot->cantidad }}

            </td>


            <!-- PRECIO -->

            <td class="p-3 border">

                L.

                {{ number_format(
                    $extra->pivot->precio,
                    2
                ) }}

            </td>


            <!-- SUBTOTAL -->

            <td class="p-3 border">

                L.

                {{ number_format(
                    $subtotal,
                    2
                ) }}

            </td>


            <!-- DESCUENTO -->

            <td class="p-3 border">

                @if($descuento > 0)

                    <span class="text-red-600 font-bold">

                        - L.

                        {{ number_format(
                            $descuento,
                            2
                        ) }}

                    </span>

                @else

                    —

                @endif

            </td>


            <!-- TOTAL FINAL -->

            <td class="p-3 border font-bold text-green-700">

                L.

                {{ number_format(
                    $totalFinal,
                    2
                ) }}

            </td>

<td class="p-2 text-center">

    <form method="POST"
          action="{{ route(
              'reservas.extras.destroy',
              $extra->pivot->id
          ) }}">

        @csrf
        @method('DELETE')

        <button type="submit"
                onclick="return confirm('¿Eliminar extra?')"
                class="bg-red-500 text-white px-2 py-1 rounded text-xs">

            Eliminar

        </button>

    </form>

</td>

        </tr>

        @empty

        <tr>

            <td colspan="6"
                class="p-6 text-center text-gray-500">

                Sin extras agregados

            </td>

        </tr>

        @endforelse

    </tbody>

</table>


            <div class="mt-6">

                <a href="{{ route('reservas.index') }}"
                   class="bg-gray-500 text-white px-4 py-2 rounded">

                    Volver

                </a>

            </div>

        </div>

    </div>

</x-app-layout>