<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Reserva
        </h2>
    </x-slot>

    <div class="p-6">

        <div class="bg-white shadow rounded-lg p-6">

            <form action="{{ route('reservas.update', $reserva->id) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="mb-4">

                    <label class="block mb-1">
                        Cliente
                    </label>

                    <select name="cliente_id"
                            class="w-full border rounded p-2">

                        @foreach($clientes as $cliente)

                            <option value="{{ $cliente->id }}"
                                {{ $reserva->cliente_id == $cliente->id ? 'selected' : '' }}>

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

                            <option value="{{ $habitacion->id }}"
                                {{ $reserva->habitacion_id == $habitacion->id ? 'selected' : '' }}>

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
                           value="{{ $reserva->fecha_entrada }}"
                           class="w-full border rounded p-2">

                </div>

                <div class="mb-4">

                    <label class="block mb-1">
                        Fecha Salida
                    </label>

                    <input type="date"
                           name="fecha_salida"
                           value="{{ $reserva->fecha_salida }}"
                           class="w-full border rounded p-2">

                </div>

                <div class="mb-4">

                    <label class="block mb-1">
                        Cantidad Personas
                    </label>

                    <input type="number"
                           name="cantidad_personas"
                           value="{{ $reserva->cantidad_personas }}"
                           class="w-full border rounded p-2">

                </div>

                <div class="mb-4">

                    <label class="block mb-1">
                        Observaciones
                    </label>

                    <textarea name="observaciones"
                              class="w-full border rounded p-2">{{ $reserva->observaciones }}</textarea>

                </div>

                <button class="bg-yellow-500 text-white px-4 py-2 rounded">

                    Actualizar Reserva

                </button>

            </form>

        </div>

    </div>

</x-app-layout>