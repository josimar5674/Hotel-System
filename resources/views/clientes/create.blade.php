<x-app-layout>
    @error('identidad')

    <div class="text-red-500 text-sm mt-1">

        {{ $message }}

    </div>

@enderror

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nuevo Cliente
        </h2>
    </x-slot>

    <div class="p-6">

        <div class="bg-white shadow rounded-lg p-6">

            <form action="{{ route('clientes.store') }}" method="POST">

                @csrf

                <div class="mb-4">
                    <label class="block mb-1">Nombre</label>

                    <input type="text"
                           name="nombre"
                           class="w-full border rounded p-2"
                           required>
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Identidad</label>

                    <input type="text"
                           name="identidad"
                           class="w-full border rounded p-2">
                </div>

                <div class="mb-4">
                    <label class="block mb-1">RTN</label>

                    <input type="text"
                           name="rtn"
                           class="w-full border rounded p-2">
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Teléfono</label>

                    <input type="text"
                           name="telefono"
                           class="w-full border rounded p-2">
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Correo</label>

                    <input type="email"
                           name="correo"
                           class="w-full border rounded p-2">
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Dirección</label>

                    <textarea name="direccion"
                              class="w-full border rounded p-2"></textarea>
                </div>

                <button class="bg-blue-500 text-white px-4 py-2 rounded">
                    Guardar Cliente
                </button>

            </form>

        </div>

    </div>

</x-app-layout>