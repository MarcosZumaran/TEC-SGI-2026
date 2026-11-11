@php
    $openTicketsQuery = \App\Models\Ticket::query();

    if (auth()->check() && auth()->user()->role === 'usuario') {
        $openTicketsQuery->where('reporter_id', auth()->id());
    }

    $openTickets = (int) $openTicketsQuery
        ->where('status', 'Abierto')
        ->count();

    $inProgressTickets = (int) \App\Models\Ticket::query()
        ->where('status', 'En Progreso')
        ->count();

    $resolvedTickets = (int) \App\Models\Ticket::query()
        ->where('status', 'Resuelto')
        ->count();

    $resolvedForAverage = \App\Models\Ticket::query()
        ->whereNotNull('resolved_at')
        ->get(['created_at', 'resolved_at']);

    $averageResolution = 0.0;

    if ($resolvedForAverage->isNotEmpty()) {
        $totalSeconds = $resolvedForAverage->sum(function ($ticket) {
            return (float) $ticket->created_at->diffInSeconds($ticket->resolved_at, true);
        });

        $averageResolution = round(($totalSeconds / 3600) / $resolvedForAverage->count(), 1);
    }
@endphp

<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="font-rajdhani text-3xl font-bold uppercase tracking-wider text-white">
                    Panel de Control
                </h1>
                <p class="mt-1 font-inter text-sm text-slate-400">
                    Resumen operativo de tickets y tiempos de atención.
                </p>
            </div>

            <p class="font-rajdhani text-sm font-semibold uppercase tracking-widest text-amber-400">
                Actualizado: {{ now()->format('d/m/Y H:i') }}
            </p>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
            <!-- Ticket abiertos -->
            <div class="tile-anim relative overflow-hidden rounded-sm bg-gradient-to-br from-[#284E8C] to-[#1e3a8a] p-6 text-white shadow-xl md:col-span-2">
                <div class="pointer-events-none absolute -right-6 -top-6 text-white/10">
                    <svg class="h-48 w-48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 5v2m0 4v2m0 4v2M9 5v2m0 4v2m0 4v2M5 7a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2V7z" />
                    </svg>
                </div>

                <div class="relative">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-sm bg-white/10">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>

                        <div>
                            <p class="font-rajdhani text-sm font-bold uppercase tracking-[0.2em] text-blue-100">
                                Tickets Abiertos
                            </p>
                            <p class="font-inter text-xs text-blue-200/80">
                                Requieren atención inicial
                            </p>
                        </div>
                    </div>

                    <p class="mt-6 font-rajdhani text-6xl font-bold leading-none">
                        {{ number_format($openTickets) }}
                    </p>

                    <div class="mt-4 h-1 w-20 bg-[#E42B35]"></div>
                </div>
            </div>

            <!-- Tickets en progreso -->
            <div class="tile-anim relative overflow-hidden rounded-sm bg-gradient-to-br from-amber-500 to-amber-700 p-6 text-white shadow-xl">
                <div class="relative">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-sm bg-white/10">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="font-rajdhani text-sm font-bold uppercase tracking-[0.2em] text-amber-100">
                            En Progreso
                        </p>
                    </div>

                    <p class="mt-6 font-rajdhani text-6xl font-bold leading-none">
                        {{ number_format($inProgressTickets) }}
                    </p>

                    <p class="mt-4 font-inter text-xs text-amber-100/80">
                        En atención por el equipo técnico
                    </p>
                </div>
            </div>

            <!-- Tickets resueltos -->
            <div class="tile-anim relative overflow-hidden rounded-sm bg-gradient-to-br from-emerald-500 to-emerald-700 p-6 text-white shadow-xl">
                <div class="relative">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-sm bg-white/10">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="font-rajdhani text-sm font-bold uppercase tracking-[0.2em] text-emerald-100">
                            Resueltos
                        </p>
                    </div>

                    <p class="mt-6 font-rajdhani text-6xl font-bold leading-none">
                        {{ number_format($resolvedTickets) }}
                    </p>

                    <p class="mt-4 font-inter text-xs text-emerald-100/80">
                        Incidencias atendidas correctamente
                    </p>
                </div>
            </div>

            <!-- Tiempo promedio -->
            <div class="tile-anim relative overflow-hidden rounded-sm border-l-4 border-[#E42B35] bg-slate-800 p-6 text-white shadow-xl md:col-span-2 lg:col-span-4">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-4">
                        <div class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-sm bg-[#E42B35]/15 text-[#E42B35]">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>

                        <div>
                            <p class="font-rajdhani text-sm font-bold uppercase tracking-[0.2em] text-slate-300">
                                Tiempo Promedio
                            </p>
                            <p class="font-inter text-xs text-slate-400">
                                Calculado sobre tickets con fecha de resolución
                            </p>
                        </div>
                    </div>

                    <div class="flex items-end gap-2">
                        <p class="font-rajdhani text-5xl font-bold leading-none text-white">
                            {{ number_format($averageResolution, 1) }}
                        </p>
                        <span class="mb-1 font-rajdhani text-lg font-semibold uppercase tracking-wider text-[#E42B35]">
                            horas
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
