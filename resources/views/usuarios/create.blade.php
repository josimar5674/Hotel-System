<x-app-layout>

    <x-slot name="header">

        <h2 class="text-xl font-bold">
            Nuevo Usuario
        </h2>

    </x-slot>

    <div class="bg-white shadow rounded-lg p-6 max-w-2xl">

        <form method="POST"
              action="{{ route('usuarios.store') }}">

            @csrf

            <div class="mb-4">

                <label class="block mb-1">
                    Nombre
                </label>

                <input type="text"
                       name="name"
                       class="w-full border rounded p-2"
                       required>

            </div>


            <div class="mb-4">

                <label class="block mb-1">
                    Correo
                </label>

                <input type="email"
                       name="email"
                       class="w-full border rounded p-2"
                       required>

            </div>


            <div class="mb-4">

                <label class="block mb-1">
                    Contraseña
                </label>

                <input type="password"
                       name="password"
                       class="w-full border rounded p-2"
                       required>

            </div>


            <div class="mb-6">

                <label class="block mb-1">
                    Rol
                </label>

                <select name="role"
                        class="w-full border rounded p-2">

                    <option value="usuario">
                        Usuario
                    </option>

                    <option value="admin">
                        Admin
                    </option>

                </select>

            </div>


            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded">

                Guardar Usuario

            </button>

        </form>

    </div>

</x-app-layout>