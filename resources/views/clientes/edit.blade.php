<x-app-layout>

@error('identidad')

    <div class="text-red-500 text-sm mt-1">

        {{ $message }}

    </div>

@enderror

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Cliente
        </h2>
    </x-slot>

    <div class="p-6">

        <div class="bg-white shadow rounded-lg p-6">

            <form action="{{ route('clientes.update', $cliente->id) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block mb-1">Nombre</label>

                    <input type="text"
                           name="nombre"
                           value="{{ $cliente->nombre }}"
                           class="w-full border rounded p-2">
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Identidad</label>

                    <input type="text"
                           name="identidad"
                           value="{{ $cliente->identidad }}"
                           class="w-full border rounded p-2">
                </div>

                <div class="mb-4">
                    <label class="block mb-1">RTN</label>

                    <input type="text"
                           name="rtn"
                           value="{{ $cliente->rtn }}"
                           class="w-full border rounded p-2">
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Teléfono</label>

                    <input type="text"
                           name="telefono"
                           value="{{ $cliente->telefono }}"
                           class="w-full border rounded p-2">
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Correo</label>

                    <input type="email"
                           name="correo"
                           value="{{ $cliente->correo }}"
                           class="w-full border rounded p-2">
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Dirección</label>

                    <textarea name="direccion"
                              class="w-full border rounded p-2">{{ $cliente->direccion }}</textarea>
                </div>

                <button class="bg-yellow-500 text-white px-4 py-2 rounded">
                    Actualizar Cliente
                </button>

            </form>

        </div>

    </div>

</x-app-layout>