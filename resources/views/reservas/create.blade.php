<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nueva Reserva
        </h2>
    </x-slot>

    <div class="p-6">

        <div class="bg-white shadow rounded-lg p-6">

            <form action="{{ route('reservas.store') }}"
                  method="POST">

                @csrf

                <div class="mb-4">
                    <label class="block mb-1">
                        Cliente
                    </label>

                    <select name="cliente_id"
                            class="w-full border rounded p-2">

                        @foreach($clientes as $cliente)

                            <option value="{{ $cliente->id }}">
                                {{ $cliente->nombre }}
                            </option>

                        @endforeach

                    </select>
                </div>

                <div class="mb-4">
                    <label class="block mb-1">
                        Habitación
                    </label>

                    <select name="habitacion_id"
                            class="w-full border rounded p-2">

                        @foreach($habitaciones as $habitacion)

                            <option value="{{ $habitacion->id }}">
                                Habitación {{ $habitacion->numero }}
                            </option>

                        @endforeach

                    </select>
                </div>

                <div class="mb-4">
                    <label class="block mb-1">
                        Fecha Entrada
                    </label>

                    <input type="date"
                           name="fecha_entrada"
                           class="w-full border rounded p-2">
                </div>

                <div class="mb-4">
                    <label class="block mb-1">
                        Fecha Salida
                    </label>

                    <input type="date"
                           name="fecha_salida"
                           class="w-full border rounded p-2">
                </div>

                <div class="mb-4">
                    <label class="block mb-1">
                        Cantidad Personas
                    </label>

                    <input type="number"
                           name="cantidad_personas"
                           class="w-full border rounded p-2"
                           value="1">
                </div>

                <div class="mb-4">
                    <label class="block mb-1">
                        Observaciones
                    </label>

                    <textarea name="observaciones"
                              class="w-full border rounded p-2"></textarea>
                </div>

                <button class="bg-blue-500 text-white px-4 py-2 rounded">

                    Guardar Reserva

                </button>

            </form>

        </div>

    </div>

</x-app-layout>