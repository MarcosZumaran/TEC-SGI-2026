<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="TEC-SGI 2026, sistema institucional para la gestión de incidencias, inventario y reportes de soporte técnico.">

        <title>TEC-SGI 2026 | Sistema de Gestión de Incidencias</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-[#071426] font-sans text-slate-100 antialiased">
        <div class="relative overflow-hidden">
            <div class="pointer-events-none absolute -right-32 -top-32 h-96 w-96 rounded-full bg-amber-400/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-40 -left-32 h-96 w-96 rounded-full bg-blue-500/10 blur-3xl"></div>

            <div class="relative mx-auto flex min-h-screen max-w-7xl flex-col px-6 lg:px-8">
                <header class="flex items-center justify-between gap-4 border-b border-white/10 py-6">
                    <a href="{{ url('/') }}" class="flex items-center" aria-label="TEC-SGI 2026, inicio">
                        <x-application-logo class="h-12 w-auto text-amber-400" />
                    </a>

                    <div class="flex items-center gap-3">
                        @auth
                            <a
                                href="{{ route('dashboard') }}"
                                class="rounded-lg bg-amber-400 px-5 py-2.5 text-sm font-bold text-[#071426] shadow-lg shadow-amber-400/20 transition hover:bg-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-300 focus:ring-offset-2 focus:ring-offset-[#071426]"
                            >
                                Ir al Dashboard
                            </a>
                        @else
                            <a
                                href="{{ route('login') }}"
                                class="rounded-lg bg-amber-400 px-5 py-2.5 text-sm font-bold text-[#071426] shadow-lg shadow-amber-400/20 transition hover:bg-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-300 focus:ring-offset-2 focus:ring-offset-[#071426]"
                            >
                                Iniciar Sesión
                            </a>
                        @endauth
                    </div>
                </header>

                <main class="flex flex-1 flex-col justify-center py-16 lg:py-24">
                    <div class="grid items-center gap-14 lg:grid-cols-2">
                        <div>
                            <span class="inline-flex items-center gap-2 rounded-full border border-amber-400/30 bg-amber-400/10 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-amber-300">
                                <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                                Mesa de ayuda institucional
                            </span>

                            <h1 class="mt-7 text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">
                                TEC-SGI <span class="text-amber-400">2026</span>
                            </h1>

                            <p class="mt-4 text-xl font-semibold text-slate-200 sm:text-2xl">
                                Sistema de Gestión de Incidencias
                            </p>

                            <p class="mt-6 max-w-xl text-base leading-7 text-slate-400">
                                Centraliza los reportes de soporte, el inventario tecnológico y los indicadores
                                de la mesa de ayuda en una sola plataforma segura, rápida y diseñada para el instituto.
                            </p>

                            <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                                @auth
                                    <a
                                        href="{{ route('dashboard') }}"
                                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-amber-400 px-6 py-3 text-sm font-bold text-[#071426] shadow-lg shadow-amber-400/20 transition hover:bg-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-300 focus:ring-offset-2 focus:ring-offset-[#071426]"
                                    >
                                        Ir al Dashboard
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                        </svg>
                                    </a>
                                @else
                                    <a
                                        href="{{ route('login') }}"
                                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-amber-400 px-6 py-3 text-sm font-bold text-[#071426] shadow-lg shadow-amber-400/20 transition hover:bg-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-300 focus:ring-offset-2 focus:ring-offset-[#071426]"
                                    >
                                        Iniciar Sesión
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                        </svg>
                                    </a>
                                @endauth

                                <a
                                    href="#caracteristicas"
                                    class="inline-flex items-center justify-center rounded-lg border border-white/15 bg-white/5 px-6 py-3 text-sm font-semibold text-white transition hover:border-amber-400/40 hover:bg-white/10"
                                >
                                    Conocer el sistema
                                </a>
                            </div>

                            <div class="mt-10 grid max-w-lg grid-cols-1 gap-3 text-sm text-slate-400 sm:grid-cols-3">
                                <span class="flex items-center gap-2">
                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                                    Tickets trazables
                                </span>
                                <span class="flex items-center gap-2">
                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                                    Inventario central
                                </span>
                                <span class="flex items-center gap-2">
                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                                    Reportes claros
                                </span>
                            </div>
                        </div>

                        <div class="relative">
                            <div class="absolute -inset-6 rounded-[2rem] bg-amber-400/10 blur-3xl"></div>

                            <div class="relative rounded-3xl border border-white/10 bg-white/5 p-6 shadow-2xl shadow-black/30 backdrop-blur">
                                <div class="flex items-center justify-between border-b border-white/10 pb-5">
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-widest text-amber-300">
                                            Panel institucional
                                        </p>
                                        <p class="mt-1 text-lg font-bold text-white">Resumen operativo</p>
                                    </div>
                                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-400 text-lg font-extrabold text-[#071426]">
                                        T
                                    </div>
                                </div>

                                <div class="mt-5 grid grid-cols-2 gap-3">
                                    <div class="rounded-2xl border border-white/10 bg-[#0B1F3A] p-4">
                                        <p class="text-xs text-slate-400">Tickets activos</p>
                                        <p class="mt-2 text-2xl font-bold text-white">12</p>
                                        <p class="mt-1 text-xs text-amber-300">En seguimiento</p>
                                    </div>
                                    <div class="rounded-2xl border border-white/10 bg-[#0B1F3A] p-4">
                                        <p class="text-xs text-slate-400">Resueltos</p>
                                        <p class="mt-2 text-2xl font-bold text-white">48</p>
                                        <p class="mt-1 text-xs text-emerald-300">Este mes</p>
                                    </div>
                                    <div class="rounded-2xl border border-white/10 bg-[#0B1F3A] p-4">
                                        <p class="text-xs text-slate-400">Inventario</p>
                                        <p class="mt-2 text-2xl font-bold text-white">126</p>
                                        <p class="mt-1 text-xs text-slate-400">Equipos registrados</p>
                                    </div>
                                    <div class="rounded-2xl border border-white/10 bg-[#0B1F3A] p-4">
                                        <p class="text-xs text-slate-400">Tiempo promedio</p>
                                        <p class="mt-2 text-2xl font-bold text-white">6.4 h</p>
                                        <p class="mt-1 text-xs text-slate-400">De resolución</p>
                                    </div>
                                </div>

                                <div class="mt-5 rounded-2xl border border-white/10 bg-[#0B1F3A] p-5">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-semibold text-white">Actividad semanal</p>
                                        <span class="rounded-full bg-emerald-400/10 px-2.5 py-1 text-xs font-semibold text-emerald-300">
                                            +18%
                                        </span>
                                    </div>
                                    <div class="mt-5 flex h-24 items-end gap-2">
                                        <div class="h-10 flex-1 rounded-t bg-slate-700"></div>
                                        <div class="h-16 flex-1 rounded-t bg-slate-600"></div>
                                        <div class="h-12 flex-1 rounded-t bg-slate-700"></div>
                                        <div class="h-20 flex-1 rounded-t bg-amber-400"></div>
                                        <div class="h-14 flex-1 rounded-t bg-slate-600"></div>
                                        <div class="h-24 flex-1 rounded-t bg-amber-400"></div>
                                        <div class="h-11 flex-1 rounded-t bg-slate-700"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>

                <section id="caracteristicas" class="border-t border-white/10 py-14">
                    <div class="mb-8 max-w-2xl">
                        <p class="text-xs font-semibold uppercase tracking-widest text-amber-300">Una plataforma completa</p>
                        <h2 class="mt-3 text-2xl font-bold text-white sm:text-3xl">
                            Todo el soporte técnico del instituto, organizado.
                        </h2>
                    </div>

                    <div class="grid gap-5 md:grid-cols-3">
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-400/10 text-amber-300">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="mt-4 font-bold text-white">Gestión de tickets</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-400">
                                Registro, asignación, seguimiento y cierre de incidencias con historial completo.
                            </p>
                        </div>

                        <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-400/10 text-amber-300">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="mt-4 font-bold text-white">Inventario tecnológico</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-400">
                                Control de equipos, ubicaciones, responsables, especificaciones y estado operativo.
                            </p>
                        </div>

                        <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-400/10 text-amber-300">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6m6 0H3m6 0h6m-6 0V9a2 2 0 012-2h2a2 2 0 012 2v10m0 0h6m-6 0h6m-6 0v-4a2 2 0 012-2h2a2 2 0 012 2v4" />
                                </svg>
                            </div>
                            <h3 class="mt-4 font-bold text-white">Reportes para dirección</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-400">
                                Indicadores de volumen, categorías, estados y tiempos de resolución en tiempo real.
                            </p>
                        </div>
                    </div>
                </section>

                <footer class="flex flex-col justify-between gap-2 border-t border-white/10 py-6 text-sm text-slate-500 sm:flex-row">
                    <p>&copy; {{ date('Y') }} TEC-SGI. Instituto Tecnológico.</p>
                    <p>Desarrollado por Marcos y Andy.</p>
                </footer>
            </div>
        </div>
    </body>
</html>
