<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>403 | Acceso Denegado - TEC-SGI 2026</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-[#071426] font-sans text-slate-100 antialiased">
        <div class="relative flex min-h-screen items-center justify-center overflow-hidden px-6 py-16">
            <div class="pointer-events-none absolute -right-32 -top-32 h-96 w-96 rounded-full bg-amber-400/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-40 -left-32 h-96 w-96 rounded-full bg-blue-500/10 blur-3xl"></div>

            <main class="relative z-10 w-full max-w-lg text-center">
                <a href="{{ url('/') }}" class="inline-flex items-center" aria-label="TEC-SGI 2026, inicio">
                    <x-application-logo class="h-16 w-auto text-amber-400" />
                </a>

                <div class="mt-10 rounded-3xl border border-white/10 bg-white/5 p-8 shadow-2xl shadow-black/30 sm:p-10">
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-amber-300">Error 403</p>
                    <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                        Acceso Denegado
                    </h1>
                    <p class="mt-4 text-sm leading-7 text-slate-400">
                        No tienes los permisos necesarios para acceder a esta sección.
                        Si crees que esto es un error, contacta al administrador del sistema.
                    </p>

                    <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                        <a
                            href="{{ route('dashboard') }}"
                            class="inline-flex items-center justify-center rounded-lg bg-amber-400 px-5 py-3 text-sm font-bold text-[#071426] shadow-lg shadow-amber-400/20 transition hover:bg-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-300 focus:ring-offset-2 focus:ring-offset-[#071426]"
                        >
                            Volver al Dashboard
                        </a>
                        <a
                            href="{{ url('/') }}"
                            class="inline-flex items-center justify-center rounded-lg border border-white/15 bg-white/5 px-5 py-3 text-sm font-semibold text-white transition hover:border-amber-400/40 hover:bg-white/10"
                        >
                            Ir al inicio
                        </a>
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>
