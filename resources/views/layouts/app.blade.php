@php($siteName = \App\Settings\GeneralSettings::siteName())

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $siteName }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Rajdhani:wght@500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles

        <style>
            [x-cloak] {
                display: none !important;
            }

            @keyframes move-dots {
                from {
                    background-position: 0 0;
                }
                to {
                    background-position: 50px 50px;
                }
            }

            .animated-bg-dots {
                background-color: #0F172A;
                background-image: radial-gradient(#1E293B 2px, transparent 2px);
                background-size: 25px 25px;
                animation: move-dots 15s linear infinite;
            }
        </style>
    </head>
    <body class="animated-bg-dots min-h-screen overflow-x-hidden font-inter text-slate-100 antialiased">
        <div x-data="{ open: false }" class="min-h-screen">
            <!-- Overlay móvil -->
            <div
                x-show="open"
                x-cloak
                x-transition.opacity
                @click="open = false"
                class="fixed inset-0 z-40 bg-black/70 md:hidden"
                aria-hidden="true"
            ></div>

            <!-- Sidebar de escritorio -->
            <aside class="fixed inset-y-0 left-0 z-40 hidden w-64 flex-col border-r border-white/10 bg-[#284E8C] text-white md:flex">
                <div class="flex h-20 flex-shrink-0 items-center border-b border-white/10 px-4">
                    <a href="{{ route('dashboard') }}" aria-label="{{ $siteName }}, inicio">
                        <img src="{{ asset('logo-tec.png') }}" class="h-12 w-auto rounded-sm bg-white object-contain p-2" alt="TEC">
                    </a>
                </div>

                <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-5">
                    <a
                        href="{{ route('dashboard') }}"
                        class="group flex items-center gap-3 rounded-sm px-4 py-3 font-rajdhani text-sm font-semibold uppercase tracking-wider transition-all duration-200 hover:scale-105 hover:bg-white/10 {{ request()->routeIs('dashboard') ? 'bg-[#E42B35] text-white shadow-lg shadow-black/30' : 'text-blue-100 hover:text-white' }}"
                    >
                        <span class="h-1.5 w-1.5 flex-shrink-0 bg-current opacity-70"></span>
                        Dashboard
                    </a>

                    <a
                        href="{{ route('tickets.create') }}"
                        class="group flex items-center gap-3 rounded-sm px-4 py-3 font-rajdhani text-sm font-semibold uppercase tracking-wider transition-all duration-200 hover:scale-105 hover:bg-white/10 {{ request()->routeIs('tickets.create') ? 'bg-[#E42B35] text-white shadow-lg shadow-black/30' : 'text-blue-100 hover:text-white' }}"
                    >
                        <span class="h-1.5 w-1.5 flex-shrink-0 bg-current opacity-70"></span>
                        Reportar Incidencia
                    </a>

                    @if (auth()->user()->role === 'directivo')
                        <div class="px-4 pb-2 pt-6 font-rajdhani text-xs font-bold uppercase tracking-[0.2em] text-blue-100/60">
                            Dirección
                        </div>

                        <a
                            href="{{ route('reports.index') }}"
                            class="group flex items-center gap-3 rounded-sm px-4 py-3 font-rajdhani text-sm font-semibold uppercase tracking-wider transition-all duration-200 hover:scale-105 hover:bg-white/10 {{ request()->routeIs('reports.*') ? 'bg-[#E42B35] text-white shadow-lg shadow-black/30' : 'text-blue-100 hover:text-white' }}"
                        >
                            <span class="h-1.5 w-1.5 flex-shrink-0 bg-current opacity-70"></span>
                            Reportes
                        </a>
                    @endif

                    @if (in_array(auth()->user()->role, ['admin', 'tecnico']))
                        <div class="px-4 pb-2 pt-6 font-rajdhani text-xs font-bold uppercase tracking-[0.2em] text-blue-100/60">
                            Gestión TI
                        </div>

                        <a
                            href="{{ route('tickets.index') }}"
                            class="group flex items-center gap-3 rounded-sm px-4 py-3 font-rajdhani text-sm font-semibold uppercase tracking-wider transition-all duration-200 hover:scale-105 hover:bg-white/10 {{ request()->routeIs('tickets.index') ? 'bg-[#E42B35] text-white shadow-lg shadow-black/30' : 'text-blue-100 hover:text-white' }}"
                        >
                            <span class="h-1.5 w-1.5 flex-shrink-0 bg-current opacity-70"></span>
                            Bandeja de Tickets
                        </a>

                        <a
                            href="{{ route('assets.index') }}"
                            class="group flex items-center gap-3 rounded-sm px-4 py-3 font-rajdhani text-sm font-semibold uppercase tracking-wider transition-all duration-200 hover:scale-105 hover:bg-white/10 {{ request()->routeIs('assets.*') ? 'bg-[#E42B35] text-white shadow-lg shadow-black/30' : 'text-blue-100 hover:text-white' }}"
                        >
                            <span class="h-1.5 w-1.5 flex-shrink-0 bg-current opacity-70"></span>
                            Inventario de Equipos
                        </a>

                        <a
                            href="{{ route('knowledge.index') }}"
                            class="group flex items-center gap-3 rounded-sm px-4 py-3 font-rajdhani text-sm font-semibold uppercase tracking-wider transition-all duration-200 hover:scale-105 hover:bg-white/10 {{ request()->routeIs('knowledge.*') ? 'bg-[#E42B35] text-white shadow-lg shadow-black/30' : 'text-blue-100 hover:text-white' }}"
                        >
                            <span class="h-1.5 w-1.5 flex-shrink-0 bg-current opacity-70"></span>
                            Base de Conocimientos
                        </a>
                    @endif

                    @if (in_array(auth()->user()->role, ['usuario', 'directivo']))
                        <div class="px-4 pb-2 pt-6 font-rajdhani text-xs font-bold uppercase tracking-[0.2em] text-blue-100/60">
                            Consulta
                        </div>

                        <a
                            href="{{ route('knowledge.index') }}"
                            class="group flex items-center gap-3 rounded-sm px-4 py-3 font-rajdhani text-sm font-semibold uppercase tracking-wider transition-all duration-200 hover:scale-105 hover:bg-white/10 {{ request()->routeIs('knowledge.*') ? 'bg-[#E42B35] text-white shadow-lg shadow-black/30' : 'text-blue-100 hover:text-white' }}"
                        >
                            <span class="h-1.5 w-1.5 flex-shrink-0 bg-current opacity-70"></span>
                            Base de Conocimientos
                        </a>
                    @endif

                    @if (auth()->user()->role === 'admin')
                        <div class="px-4 pb-2 pt-6 font-rajdhani text-xs font-bold uppercase tracking-[0.2em] text-blue-100/60">
                            Administración
                        </div>

                        <a
                            href="{{ route('reports.index') }}"
                            class="group flex items-center gap-3 rounded-sm px-4 py-3 font-rajdhani text-sm font-semibold uppercase tracking-wider transition-all duration-200 hover:scale-105 hover:bg-white/10 {{ request()->routeIs('reports.*') ? 'bg-[#E42B35] text-white shadow-lg shadow-black/30' : 'text-blue-100 hover:text-white' }}"
                        >
                            <span class="h-1.5 w-1.5 flex-shrink-0 bg-current opacity-70"></span>
                            Reportes
                        </a>

                        <a
                            href="{{ route('users.index') }}"
                            class="group flex items-center gap-3 rounded-sm px-4 py-3 font-rajdhani text-sm font-semibold uppercase tracking-wider transition-all duration-200 hover:scale-105 hover:bg-white/10 {{ request()->routeIs('users.*') ? 'bg-[#E42B35] text-white shadow-lg shadow-black/30' : 'text-blue-100 hover:text-white' }}"
                        >
                            <span class="h-1.5 w-1.5 flex-shrink-0 bg-current opacity-70"></span>
                            Usuarios
                        </a>

                        <a
                            href="{{ route('settings.index') }}"
                            class="group flex items-center gap-3 rounded-sm px-4 py-3 font-rajdhani text-sm font-semibold uppercase tracking-wider transition-all duration-200 hover:scale-105 hover:bg-white/10 {{ request()->routeIs('settings.*') ? 'bg-[#E42B35] text-white shadow-lg shadow-black/30' : 'text-blue-100 hover:text-white' }}"
                        >
                            <span class="h-1.5 w-1.5 flex-shrink-0 bg-current opacity-70"></span>
                            Configuración
                        </a>
                    @endif
                </nav>

                <div class="flex-shrink-0 border-t border-white/10 p-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-sm bg-[#E42B35] font-rajdhani text-sm font-bold text-white">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <div class="truncate font-rajdhani text-sm font-semibold uppercase tracking-wide text-white">{{ auth()->user()->name }}</div>
                            <div class="text-xs capitalize text-blue-100/70">{{ auth()->user()->role }}</div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Sidebar móvil -->
            <aside
                x-show="open"
                x-cloak
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
                class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col bg-[#284E8C] text-white shadow-2xl shadow-black/50 md:hidden"
                aria-label="Navegación móvil"
            >
                <div class="flex h-20 flex-shrink-0 items-center justify-between border-b border-white/10 px-4">
                    <a href="{{ route('dashboard') }}" aria-label="{{ $siteName }}, inicio" @click="open = false">
                        <img src="{{ asset('logo-tec.png') }}" class="h-12 w-auto rounded-sm bg-white object-contain p-2" alt="TEC">
                    </a>
                    <button
                        type="button"
                        @click="open = false"
                        class="rounded-sm p-2 text-blue-100 transition hover:bg-white/10 hover:text-white"
                        aria-label="Cerrar menú"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-5" @click="open = false">
                    <a
                        href="{{ route('dashboard') }}"
                        class="group flex items-center gap-3 rounded-sm px-4 py-3 font-rajdhani text-sm font-semibold uppercase tracking-wider transition-all duration-200 hover:scale-105 hover:bg-white/10 {{ request()->routeIs('dashboard') ? 'bg-[#E42B35] text-white shadow-lg shadow-black/30' : 'text-blue-100 hover:text-white' }}"
                    >
                        <span class="h-1.5 w-1.5 flex-shrink-0 bg-current opacity-70"></span>
                        Dashboard
                    </a>

                    <a
                        href="{{ route('tickets.create') }}"
                        class="group flex items-center gap-3 rounded-sm px-4 py-3 font-rajdhani text-sm font-semibold uppercase tracking-wider transition-all duration-200 hover:scale-105 hover:bg-white/10 {{ request()->routeIs('tickets.create') ? 'bg-[#E42B35] text-white shadow-lg shadow-black/30' : 'text-blue-100 hover:text-white' }}"
                    >
                        <span class="h-1.5 w-1.5 flex-shrink-0 bg-current opacity-70"></span>
                        Reportar Incidencia
                    </a>

                    @if (auth()->user()->role === 'directivo')
                        <div class="px-4 pb-2 pt-6 font-rajdhani text-xs font-bold uppercase tracking-[0.2em] text-blue-100/60">
                            Dirección
                        </div>

                        <a
                            href="{{ route('reports.index') }}"
                            class="group flex items-center gap-3 rounded-sm px-4 py-3 font-rajdhani text-sm font-semibold uppercase tracking-wider transition-all duration-200 hover:scale-105 hover:bg-white/10 {{ request()->routeIs('reports.*') ? 'bg-[#E42B35] text-white shadow-lg shadow-black/30' : 'text-blue-100 hover:text-white' }}"
                        >
                            <span class="h-1.5 w-1.5 flex-shrink-0 bg-current opacity-70"></span>
                            Reportes
                        </a>
                    @endif

                    @if (in_array(auth()->user()->role, ['admin', 'tecnico']))
                        <div class="px-4 pb-2 pt-6 font-rajdhani text-xs font-bold uppercase tracking-[0.2em] text-blue-100/60">
                            Gestión TI
                        </div>

                        <a
                            href="{{ route('tickets.index') }}"
                            class="group flex items-center gap-3 rounded-sm px-4 py-3 font-rajdhani text-sm font-semibold uppercase tracking-wider transition-all duration-200 hover:scale-105 hover:bg-white/10 {{ request()->routeIs('tickets.index') ? 'bg-[#E42B35] text-white shadow-lg shadow-black/30' : 'text-blue-100 hover:text-white' }}"
                        >
                            <span class="h-1.5 w-1.5 flex-shrink-0 bg-current opacity-70"></span>
                            Bandeja de Tickets
                        </a>

                        <a
                            href="{{ route('assets.index') }}"
                            class="group flex items-center gap-3 rounded-sm px-4 py-3 font-rajdhani text-sm font-semibold uppercase tracking-wider transition-all duration-200 hover:scale-105 hover:bg-white/10 {{ request()->routeIs('assets.*') ? 'bg-[#E42B35] text-white shadow-lg shadow-black/30' : 'text-blue-100 hover:text-white' }}"
                        >
                            <span class="h-1.5 w-1.5 flex-shrink-0 bg-current opacity-70"></span>
                            Inventario de Equipos
                        </a>

                        <a
                            href="{{ route('knowledge.index') }}"
                            class="group flex items-center gap-3 rounded-sm px-4 py-3 font-rajdhani text-sm font-semibold uppercase tracking-wider transition-all duration-200 hover:scale-105 hover:bg-white/10 {{ request()->routeIs('knowledge.*') ? 'bg-[#E42B35] text-white shadow-lg shadow-black/30' : 'text-blue-100 hover:text-white' }}"
                        >
                            <span class="h-1.5 w-1.5 flex-shrink-0 bg-current opacity-70"></span>
                            Base de Conocimientos
                        </a>
                    @endif

                    @if (in_array(auth()->user()->role, ['usuario', 'directivo']))
                        <div class="px-4 pb-2 pt-6 font-rajdhani text-xs font-bold uppercase tracking-[0.2em] text-blue-100/60">
                            Consulta
                        </div>

                        <a
                            href="{{ route('knowledge.index') }}"
                            class="group flex items-center gap-3 rounded-sm px-4 py-3 font-rajdhani text-sm font-semibold uppercase tracking-wider transition-all duration-200 hover:scale-105 hover:bg-white/10 {{ request()->routeIs('knowledge.*') ? 'bg-[#E42B35] text-white shadow-lg shadow-black/30' : 'text-blue-100 hover:text-white' }}"
                        >
                            <span class="h-1.5 w-1.5 flex-shrink-0 bg-current opacity-70"></span>
                            Base de Conocimientos
                        </a>
                    @endif

                    @if (auth()->user()->role === 'admin')
                        <div class="px-4 pb-2 pt-6 font-rajdhani text-xs font-bold uppercase tracking-[0.2em] text-blue-100/60">
                            Administración
                        </div>

                        <a
                            href="{{ route('reports.index') }}"
                            class="group flex items-center gap-3 rounded-sm px-4 py-3 font-rajdhani text-sm font-semibold uppercase tracking-wider transition-all duration-200 hover:scale-105 hover:bg-white/10 {{ request()->routeIs('reports.*') ? 'bg-[#E42B35] text-white shadow-lg shadow-black/30' : 'text-blue-100 hover:text-white' }}"
                        >
                            <span class="h-1.5 w-1.5 flex-shrink-0 bg-current opacity-70"></span>
                            Reportes
                        </a>

                        <a
                            href="{{ route('users.index') }}"
                            class="group flex items-center gap-3 rounded-sm px-4 py-3 font-rajdhani text-sm font-semibold uppercase tracking-wider transition-all duration-200 hover:scale-105 hover:bg-white/10 {{ request()->routeIs('users.*') ? 'bg-[#E42B35] text-white shadow-lg shadow-black/30' : 'text-blue-100 hover:text-white' }}"
                        >
                            <span class="h-1.5 w-1.5 flex-shrink-0 bg-current opacity-70"></span>
                            Usuarios
                        </a>

                        <a
                            href="{{ route('settings.index') }}"
                            class="group flex items-center gap-3 rounded-sm px-4 py-3 font-rajdhani text-sm font-semibold uppercase tracking-wider transition-all duration-200 hover:scale-105 hover:bg-white/10 {{ request()->routeIs('settings.*') ? 'bg-[#E42B35] text-white shadow-lg shadow-black/30' : 'text-blue-100 hover:text-white' }}"
                        >
                            <span class="h-1.5 w-1.5 flex-shrink-0 bg-current opacity-70"></span>
                            Configuración
                        </a>
                    @endif
                </nav>

                <div class="flex-shrink-0 border-t border-white/10 p-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-sm bg-[#E42B35] font-rajdhani text-sm font-bold text-white">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <div class="truncate font-rajdhani text-sm font-semibold uppercase tracking-wide text-white">{{ auth()->user()->name }}</div>
                            <div class="text-xs capitalize text-blue-100/70">{{ auth()->user()->role }}</div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Contenido principal -->
            <div class="flex min-h-screen flex-col md:pl-64">
                <!-- Header -->
                <header class="sticky top-0 z-30 flex h-20 items-center justify-between gap-4 border-b border-white/10 bg-slate-800 px-4 text-white shadow-lg shadow-black/50 sm:px-6">
                    <div class="flex min-w-0 items-center gap-3">
                        <button
                            type="button"
                            @click="open = true"
                            class="rounded-sm p-2 text-slate-200 transition hover:bg-white/10 hover:text-white md:hidden"
                            aria-label="Abrir menú"
                        >
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <div class="min-w-0">
                            <div class="truncate font-rajdhani text-lg font-bold uppercase tracking-wider text-white sm:text-xl">
                                {{ $siteName }}
                            </div>
                            <div class="hidden truncate font-inter text-xs text-slate-400 sm:block">
                                Sistema de Gestión de Incidencias
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-shrink-0 items-center gap-3">
                        <livewire:notification-bell />

                        <div class="hidden min-w-0 text-right sm:block">
                            <div class="max-w-[12rem] truncate font-rajdhani text-sm font-semibold uppercase tracking-wide text-white">
                                {{ auth()->user()->name }}
                            </div>
                            <div class="font-inter text-xs capitalize text-amber-400">{{ auth()->user()->role }}</div>
                        </div>

                        <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-sm bg-[#E42B35] font-rajdhani text-sm font-bold text-white">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="font-rajdhani text-sm font-bold uppercase tracking-wider text-[#E42B35] transition hover:text-red-400">
                                Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </header>

                @if (get_setting('general.maintenance_mode', false))
                    <div class="border-b border-amber-400/30 bg-amber-400/10 px-4 py-2 text-center font-rajdhani text-sm font-semibold uppercase tracking-wider text-amber-200">
                        Sistema en mantenimiento programado. Algunas funciones pueden no estar disponibles.
                    </div>
                @endif

                <!-- Contenido -->
                <main class="flex-1">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @livewireScripts
    </body>
</html>
