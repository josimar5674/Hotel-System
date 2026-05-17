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

                <div class="grid grid-cols-3 gap-4">

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


            <table class="w-full border">

                <thead>

                    <tr class="bg-gray-200">

                        <th class="p-2">
                            Extra
                        </th>

                        <th class="p-2">
                            Cantidad
                        </th>

                        <th class="p-2">
                            Precio
                        </th>

                        <th class="p-2">
                            Total
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($reserva->extras as $extra)

                    <tr class="border-t">

                        <td class="p-2">

                            {{ $extra->nombre }}

                        </td>

                        <td class="p-2">

                            {{ $extra->pivot->cantidad }}

                        </td>

                        <td class="p-2">

                            L. {{ number_format($extra->pivot->precio, 2) }}

                        </td>

                        <td class="p-2">

                            L.

                            {{ number_format(
                                $extra->pivot->cantidad *
                                $extra->pivot->precio,
                                2
                            ) }}

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="4"
                            class="p-4 text-center text-gray-500">

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