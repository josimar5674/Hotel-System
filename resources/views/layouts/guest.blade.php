<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        {{ config('app.name', 'Posada Doña Estefana') }} | Iniciar sesión
    </title>

    <!-- Fuentes -->
    <link rel="preconnect" href="https://fonts.bunny.net">

    <link
        href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap"
        rel="stylesheet"
    />

    <!-- Estilos y scripts de Laravel -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Figtree', sans-serif;
        }

        .login-background {
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 20px;
            position: relative;

            background-image:
                linear-gradient(
                    rgba(12, 18, 28, 0.45),
                    rgba(12, 18, 28, 0.60)
                ),
            url('{{ asset('images/hotel-login.jpg') }}');
                        background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .login-container {
            position: relative;
            width: 100%;
            max-width: 440px;
            z-index: 1;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 20px;
            overflow: hidden;

            box-shadow:
                0 25px 60px rgba(0, 0, 0, 0.30),
                0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .login-header {
            padding: 35px 30px 25px;
            text-align: center;
        }

        .hotel-logo {
            max-width: 180px;
            max-height: 110px;
            width: auto;
            height: auto;
            object-fit: contain;
            display: block;
            margin: 0 auto 20px;
        }

        .hotel-name {
            font-size: 25px;
            font-weight: 700;
            color: #1f2937;
            line-height: 1.3;
            overflow-wrap: anywhere;
        }

        .hotel-subtitle {
            margin-top: 8px;
            color: #6b7280;
            font-size: 14px;
        }

        .login-divider {
            width: 55px;
            height: 4px;
            background: #b08d57;
            border-radius: 10px;
            margin: 20px auto 0;
        }

        .login-content {
            padding: 10px 32px 32px;
        }

        .login-title {
            text-align: center;
            font-size: 19px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 25px;
        }

        .login-footer {
            padding: 16px 20px;
            text-align: center;
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 12px;
        }

        @media (max-width: 480px) {
            .login-background {
                padding: 20px 14px;
            }

            .login-header {
                padding: 28px 20px 20px;
            }

            .login-content {
                padding: 10px 22px 25px;
            }

            .hotel-name {
                font-size: 22px;
            }

            .hotel-logo {
                max-height: 90px;
            }
        }
    </style>
</head>

<body class="antialiased">

    @php
        $config = \App\Models\ConfiguracionFiscal::first();
    @endphp

    <main class="login-background">

        <div class="login-container">

            <div class="login-card">

                {{-- ENCABEZADO DEL HOTEL --}}
                <header class="login-header">

                    @if($config && $config->logo)

                        <img
                            src="{{ asset('storage/' . $config->logo) }}"
                            alt="Logo del hotel"
                            class="hotel-logo"
                        >

                    @endif

                    <h1 class="hotel-name">
                        {{ $config->nombre_negocio ?? config('app.name', 'Hotel') }}
                    </h1>

                    <p class="hotel-subtitle">
                        Sistema de administración hotelera
                    </p>

                    <div class="login-divider"></div>

                </header>


                {{-- FORMULARIO --}}
                <section class="login-content">

                    {{ $slot }}

                </section>


                {{-- PIE DE PÁGINA --}}
                <footer class="login-footer">

                    {{ $config->nombre_negocio ?? config('app.name', 'Hotel') }}

                    &middot;

                    Acceso seguro al sistema

                </footer>

            </div>

        </div>

    </main>

</body>

</html>