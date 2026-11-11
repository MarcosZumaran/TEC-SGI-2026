<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TEC-SGI 2026') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Rajdhani:wght@500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            [x-cloak] {
                display: none !important;
            }

            body {
                background-color: #0f172a;
                background-image: radial-gradient(#1e293b 1px, transparent 1px);
                background-size: 20px 20px;
            }

            .guest-panel label {
                color: #cbd5e1;
                font-family: 'Inter', sans-serif;
                font-size: 0.875rem;
                font-weight: 500;
            }

            .guest-panel input[type="email"],
            .guest-panel input[type="password"],
            .guest-panel input[type="text"] {
                width: 100%;
                margin-top: 0.35rem;
                border: 1px solid #334155;
                border-radius: 2px;
                background-color: #0f172a;
                color: #ffffff;
                padding: 0.75rem 1rem;
                font-family: 'Inter', sans-serif;
                transition: border-color 150ms ease, box-shadow 150ms ease;
            }

            .guest-panel input[type="email"]::placeholder,
            .guest-panel input[type="password"]::placeholder,
            .guest-panel input[type="text"]::placeholder {
                color: #64748b;
            }

            .guest-panel input[type="email"]:focus,
            .guest-panel input[type="password"]:focus,
            .guest-panel input[type="text"]:focus {
                border-color: #E42B35;
                outline: none;
                box-shadow: 0 0 0 1px #E42B35;
            }

            .guest-panel input[type="checkbox"] {
                border: 1px solid #475569;
                border-radius: 2px;
                background-color: #0f172a;
                color: #E42B35;
            }

            .guest-panel input[type="checkbox"]:focus {
                outline: none;
                box-shadow: 0 0 0 2px #E42B35;
            }

            .guest-panel .text-gray-600,
            .guest-panel .text-gray-700 {
                color: #cbd5e1;
            }

            .guest-panel a {
                color: #fca5a5;
                font-family: 'Inter', sans-serif;
                transition: color 150ms ease;
            }

            .guest-panel a:hover {
                color: #fecaca;
            }

            .guest-panel button[type="submit"] {
                border-radius: 2px;
                background-color: #E42B35;
                color: #ffffff;
                font-family: 'Rajdhani', sans-serif;
                font-weight: 700;
                letter-spacing: 0.12em;
                text-transform: uppercase;
                padding: 0.7rem 1.5rem;
                transition: background-color 150ms ease, transform 150ms ease;
            }

            .guest-panel button[type="submit"]:hover {
                background-color: #b91c1c;
                transform: translateY(-1px);
            }

            .guest-panel button[type="submit"]:focus {
                outline: none;
                box-shadow: 0 0 0 3px rgba(228, 43, 53, 0.35);
            }
        </style>
    </head>
    <body class="min-h-screen font-inter text-slate-100 antialiased">
        <div class="grid min-h-screen grid-cols-1 lg:h-screen lg:grid-cols-2">
            <!-- Lado visual -->
            <div class="relative flex flex-col justify-center overflow-hidden bg-[#284E8C] px-8 py-12 lg:px-16">
                <div class="pointer-events-none absolute -right-32 -top-32 h-96 w-96 rounded-full bg-white/10 blur-3xl"></div>
                <div class="pointer-events-none absolute -bottom-40 -left-32 h-96 w-96 rounded-full bg-[#E42B35]/20 blur-3xl"></div>

                <div class="relative z-10 mx-auto w-full max-w-lg">
                    <a href="{{ url('/') }}" class="inline-block" aria-label="TEC-SGI 2026, inicio">
                        <img src="{{ asset('logo-tec.png') }}" class="h-24 w-auto rounded-sm bg-white object-contain p-3 shadow-2xl shadow-black/30" alt="TEC">
                    </a>

                    <p class="mt-8 font-rajdhani text-sm font-bold uppercase tracking-[0.35em] text-blue-200">
                        Instituto TEC
                    </p>

                    <h1 class="mt-3 font-rajdhani text-4xl font-bold uppercase leading-tight tracking-wider text-white sm:text-5xl">
                        TEC-SGI 2026
                    </h1>

                    <p class="mt-4 max-w-md font-inter text-base leading-relaxed text-blue-100">
                        Sistema de Gestión de Incidencias. Centraliza tus reportes, el inventario tecnológico y la atención técnica del instituto.
                    </p>

                    <div class="mt-8 grid grid-cols-2 gap-3">
                        <div class="border-l-4 border-[#E42B35] bg-white/10 p-4">
                            <p class="font-rajdhani text-lg font-bold uppercase tracking-wider text-white">
                                Reporta
                            </p>
                            <p class="mt-1 font-inter text-xs text-blue-100">
                                Incidencias de forma rápida.
                            </p>
                        </div>
                        <div class="border-l-4 border-amber-400 bg-white/10 p-4">
                            <p class="font-rajdhani text-lg font-bold uppercase tracking-wider text-white">
                                Gestiona
                            </p>
                            <p class="mt-1 font-inter text-xs text-blue-100">
                                Equipos y soporte técnico.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lado del formulario -->
            <div class="flex items-center justify-center bg-slate-900 px-4 py-10 sm:px-6 lg:overflow-y-auto lg:px-8">
                <div class="guest-panel w-full max-w-md">
                    <div class="mb-5">
                        <p class="font-rajdhani text-xs font-bold uppercase tracking-[0.3em] text-[#E42B35]">
                            Acceso al sistema
                        </p>
                        <h2 class="mt-2 font-rajdhani text-3xl font-bold uppercase tracking-wider text-white">
                            Iniciar Sesión
                        </h2>
                        <p class="mt-2 font-inter text-sm text-slate-400">
                            Ingresa con tus credenciales institucionales.
                        </p>
                    </div>

                    <div class="rounded-sm border border-slate-700 bg-slate-800 p-6 shadow-2xl shadow-black/50 sm:p-8">
                        {{ $slot }}
                    </div>

                    <p class="mt-6 text-center font-inter text-xs text-slate-500">
                        &copy; {{ date('Y') }} TEC-SGI. Instituto Tecnológico.
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
