<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Habitación
        </h2>
    </x-slot>

    <div class="p-6">

        <div class="bg-white shadow rounded-lg p-6">

            <form action="{{ route('habitaciones.update', $habitacion->id) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block mb-1">Número</label>

                    <input type="text"
                           name="numero"
                           value="{{ $habitacion->numero }}"
                           class="w-full border rounded p-2">
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Tipo</label>

                    <select name="tipo"
                            class="w-full border rounded p-2">

                        <option value="simple"
                            {{ $habitacion->tipo == 'simple' ? 'selected' : '' }}>
                            Simple
                        </option>

                        <option value="doble"
                            {{ $habitacion->tipo == 'doble' ? 'selected' : '' }}>
                            Doble
                        </option>

                        <option value="matrimonial"
                            {{ $habitacion->tipo == 'matrimonial' ? 'selected' : '' }}>
                            Matrimonial
                        </option>

                        <option value="suite"
                            {{ $habitacion->tipo == 'suite' ? 'selected' : '' }}>
                            Suite
                        </option>

                    </select>
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Precio</label>

                    <input type="number"
                           step="0.01"
                           name="precio"
                           value="{{ $habitacion->precio }}"
                           class="w-full border rounded p-2">
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Estado</label>

                    <select name="estado"
                            class="w-full border rounded p-2">

                        <option value="disponible"
                            {{ $habitacion->estado == 'disponible' ? 'selected' : '' }}>
                            Disponible
                        </option>

                        <option value="ocupada"
                            {{ $habitacion->estado == 'ocupada' ? 'selected' : '' }}>
                            Ocupada
                        </option>

                        <option value="limpieza"
                            {{ $habitacion->estado == 'limpieza' ? 'selected' : '' }}>
                            Limpieza
                        </option>

                        <option value="mantenimiento"
                            {{ $habitacion->estado == 'mantenimiento' ? 'selected' : '' }}>
                            Mantenimiento
                        </option>

                    </select>
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Descripción</label>

                    <textarea name="descripcion"
                              class="w-full border rounded p-2">{{ $habitacion->descripcion }}</textarea>
                </div>

                <button class="bg-yellow-500 text-white px-4 py-2 rounded">
                    Actualizar
                </button>

            </form>

        </div>

    </div>

</x-app-layout>