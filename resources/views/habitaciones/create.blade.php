<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nueva Habitación
        </h2>
    </x-slot>

    <div class="p-6">

        <div class="bg-white shadow rounded-lg p-6">

            <form action="{{ route('habitaciones.store') }}" method="POST">

                @csrf

                <div class="mb-4">
                    <label class="block mb-1">Número</label>

                    <input type="text"
                           name="numero"
                           class="w-full border rounded p-2"
                           required>
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Tipo</label>

                    <select name="tipo"
                            class="w-full border rounded p-2">

                        <option value="simple">Simple</option>
                        <option value="doble">Doble</option>
                        <option value="matrimonial">Matrimonial</option>
                        <option value="suite">Suite</option>

                    </select>
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Precio</label>

                    <input type="number"
                           step="0.01"
                           name="precio"
                           class="w-full border rounded p-2"
                           required>
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Estado</label>

                    <select name="estado"
                            class="w-full border rounded p-2">

                        <option value="disponible">Disponible</option>
                        <option value="ocupada">Ocupada</option>
                        <option value="limpieza">Limpieza</option>
                        <option value="mantenimiento">Mantenimiento</option>

                    </select>
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Descripción</label>

                    <textarea name="descripcion"
                              class="w-full border rounded p-2"></textarea>
                </div>

                <button class="bg-blue-500 text-white px-4 py-2 rounded">
                    Guardar
                </button>

            </form>

        </div>

    </div>

</x-app-layout>