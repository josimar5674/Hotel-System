<x-app-layout>

    <x-slot name="header">

        <h2 class="text-xl font-bold">
            Usuarios
        </h2>

    </x-slot>

    <div class="bg-white shadow rounded-lg p-6">

        <div class="mb-4">

            <a href="{{ route('usuarios.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded">

                Nuevo Usuario

            </a>

        </div>


        <table class="w-full border">

            <thead>

                <tr class="bg-gray-200">

                    <th class="p-2">Nombre</th>

                    <th class="p-2">Correo</th>

                    <th class="p-2">Rol</th>

                    <th class="p-2">Acciones</th>

                </tr>

            </thead>

            <tbody>

                @foreach($usuarios as $usuario)

                <tr class="border-t">

                    <td class="p-2">

                        {{ $usuario->name }}

                    </td>

                    <td class="p-2">

                        {{ $usuario->email }}

                    </td>

                    <td class="p-2">

                        {{ $usuario->role }}

                    </td>

                    <td class="p-2">

                        <a href="{{ route(
                            'usuarios.edit',
                            $usuario->id
                        ) }}"
                           class="bg-yellow-500 text-white px-3 py-1 rounded">

                            Editar

                        </a>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</x-app-layout>