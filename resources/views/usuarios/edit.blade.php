<x-app-layout>

    <x-slot name="header">

        <h2 class="text-xl font-bold">
            Editar Usuario
        </h2>

    </x-slot>

    <div class="bg-white shadow rounded-lg p-6 max-w-2xl">

        <form method="POST"
              action="{{ route(
                  'usuarios.update',
                  $usuario->id
              ) }}">

            @csrf
            @method('PUT')


            <div class="mb-4">

                <label class="block mb-1">
                    Nombre
                </label>

                <input type="text"
                       name="name"
                       value="{{ $usuario->name }}"
                       class="w-full border rounded p-2"
                       required>

            </div>


            <div class="mb-4">

                <label class="block mb-1">
                    Correo
                </label>

                <input type="email"
                       name="email"
                       value="{{ $usuario->email }}"
                       class="w-full border rounded p-2"
                       required>

            </div>


            <div class="mb-4">

                <label class="block mb-1">
                    Nueva Contraseña
                </label>

                <input type="password"
                       name="password"
                       class="w-full border rounded p-2">

                <small class="text-gray-500">

                    Dejar vacío para mantener actual

                </small>

            </div>


            <div class="mb-6">

                <label class="block mb-1">
                    Rol
                </label>

                <select name="role"
                        class="w-full border rounded p-2">

                    <option value="usuario"
                        {{ $usuario->role == 'usuario'
                            ? 'selected'
                            : '' }}>

                        Usuario

                    </option>

                    <option value="admin"
                        {{ $usuario->role == 'admin'
                            ? 'selected'
                            : '' }}>

                        Admin

                    </option>

                </select>

            </div>


            <button type="submit"
                    class="bg-yellow-500 text-white px-4 py-2 rounded">

                Actualizar Usuario

            </button>

        </form>

    </div>

</x-app-layout>