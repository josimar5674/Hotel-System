<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    <title>
        {{ config('app.name', 'Laravel') }}
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>

<body class="font-sans antialiased bg-gray-100">

    <div class="flex min-h-screen">

        <!-- SIDEBAR -->

    <aside id="sidebar"
       class="w-64 h-screen sticky top-0 transition-all duration-300 bg-gray-900 text-white flex flex-col">

            <!-- LOGO -->

            <div class="p-6 border-b border-gray-700">

                <div class="flex items-center justify-between">

                    <h1 id="sidebarTitle"
                        class="text-2xl font-bold tracking-wide">

                        🏨 HOTEL SYSTEM

                    </h1>

                    <button onclick="toggleSidebar()"
                            class="bg-gray-800 px-2 py-1 rounded hover:bg-gray-700">

                        ☰

                    </button>

                </div>

            </div>


            <!-- MENU -->

            <nav class="flex-1 p-4 space-y-2">

                @php
                    $user = auth()->user();
                @endphp

                <!-- CLIENTES -->

<a href="{{ route('clientes.index') }}"
   class="block px-4 py-2 rounded hover:bg-gray-700 transition">

    <div class="flex items-center gap-3">

        <span class="text-xl">
            👥
        </span>

        <span class="menu-text">
            Clientes
        </span>

    </div>

</a>


                <!-- RESERVAS -->

                <a href="{{ route('reservas.index') }}"
                   class="block px-4 py-2 rounded hover:bg-gray-700 transition">

                    <div class="flex items-center gap-3">

                        <span class="text-xl">
                            📅
                        </span>

                        <span class="menu-text">
                            Reservas
                        </span>

                    </div>

                </a>


                <!-- ADMIN -->

                @if($user->role == 'admin')

                    <hr class="border-gray-700 my-4">

                    <p class="text-xs uppercase text-gray-400 px-4">

                        <span class="menu-text">
                            Administración
                        </span>

                    </p>


                    <!-- HABITACIONES -->

                    <a href="{{ route('habitaciones.index') }}"
                       class="block px-4 py-2 rounded hover:bg-gray-700 transition">

                        <div class="flex items-center gap-3">

                            <span class="text-xl">
                                🛏
                            </span>

                            <span class="menu-text">
                                Habitaciones
                            </span>

                        </div>

                    </a>


                    <!-- CONFIGURACIONES -->

                    <a href="{{ route('configuracion-fiscal.edit') }}"
                       class="block px-4 py-2 rounded hover:bg-gray-700 transition">

                        <div class="flex items-center gap-3">

                            <span class="text-xl">
                                ⚙️
                            </span>

                            <span class="menu-text">
                                Configuración
                            </span>

                        </div>

                    </a>


                    <!-- USUARIOS -->

                   <a href="{{ route('usuarios.index') }}"
                       class="block px-4 py-2 rounded hover:bg-gray-700 transition">

                        <div class="flex items-center gap-3">

                            <span class="text-xl">
                                👤
                            </span>

                            <span class="menu-text">
                                Usuarios
                            </span>

                        </div>

                    </a>


                    <!-- REPORTES -->

                    <a href="#"
                       class="block px-4 py-2 rounded hover:bg-gray-700 transition">

                        <div class="flex items-center gap-3">

                            <span class="text-xl">
                                📊
                            </span>

                            <span class="menu-text">
                                Reportes
                            </span>

                        </div>

                    </a>

                @endif

            </nav>


            <!-- USER -->

            <div class="p-4 border-t border-gray-700">

                <div class="mb-3 text-sm text-gray-300">

                    <span class="menu-text">
                        {{ auth()->user()->name }}
                    </span>

                </div>


                <form method="POST"
                      action="{{ route('logout') }}">

                    @csrf

                    <button type="submit"
                            class="w-full bg-red-600 hover:bg-red-700 px-4 py-2 rounded text-sm flex items-center justify-center gap-2">

                        <span class="text-xl">
                            🚪
                        </span>

                        <span class="menu-text">
                            Cerrar Sesión
                        </span>

                    </button>

                </form>

            </div>

        </aside>


        <!-- CONTENIDO -->

  <main class="flex-1 h-screen overflow-y-auto p-6">

            @if (isset($header))

                <div class="mb-6">

                    {{ $header }}

                </div>

            @endif

            {{ $slot }}

        </main>

    </div>


    <script>

    function toggleSidebar()
    {
        const sidebar = document.getElementById(
            'sidebar'
        );

        const title = document.getElementById(
            'sidebarTitle'
        );

        const texts = document.querySelectorAll(
            '.menu-text'
        );


        // COLAPSAR

        if(sidebar.classList.contains('w-64')) {

            sidebar.classList.remove('w-64');

            sidebar.classList.add('w-20');

            title.classList.add('hidden');

            texts.forEach(text => {

                text.classList.add('hidden');

            });

        }

        // EXPANDIR

        else {

            sidebar.classList.remove('w-20');

            sidebar.classList.add('w-64');

            title.classList.remove('hidden');

            texts.forEach(text => {

                text.classList.remove('hidden');

            });

        }
    }

    </script>

</body>

</html>