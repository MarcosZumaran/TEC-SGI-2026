<div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="mb-8">
        <p class="font-rajdhani text-xs font-semibold uppercase tracking-[0.3em] text-[#E42B35]">Actividad</p>
        <h1 class="mt-1 font-rajdhani text-3xl font-bold uppercase tracking-wider text-white">Centro de Notificaciones</h1>
        <p class="mt-2 text-sm text-slate-400">Revisa las actualizaciones de tus tickets y los avisos del sistema.</p>
    </div>

    @if ($notifications->isEmpty())
        <div class="rounded-sm border border-slate-700 bg-slate-800/95 p-10 text-center shadow-xl shadow-black/30">
            <p class="font-rajdhani text-lg font-bold uppercase tracking-wider text-white">No tienes notificaciones</p>
            <p class="mt-2 text-sm text-slate-400">Cuando se asigne o actualice un ticket, aparecerá aquí.</p>
        </div>
    @else
        @php
            $groups = $notifications->getCollection()->groupBy(function ($notification) {
                $date = $notification->created_at;

                if ($date->isToday()) {
                    return 'Hoy';
                }

                if ($date->isYesterday()) {
                    return 'Ayer';
                }

                return $date->copy()->locale('es')->translatedFormat('d \d\e F \d\e Y');
            });
        @endphp

        <div class="space-y-8">
            @foreach ($groups as $label => $group)
                <section>
                    <h2 class="mb-3 font-rajdhani text-sm font-bold uppercase tracking-[0.2em] text-slate-400">
                        {{ $label }}
                    </h2>

                    <div class="space-y-3">
                        @foreach ($group as $notification)
                            <button
                                type="button"
                                wire:key="notification-list-{{ $notification->id }}"
                                wire:click="markAsRead('{{ $notification->id }}')"
                                @class([
                                    'group flex w-full items-start gap-4 rounded-sm border border-slate-700 bg-slate-800/95 p-4 text-left shadow-lg shadow-black/20 transition hover:border-slate-500 hover:bg-slate-800',
                                    'border-l-4 border-l-[#E42B35]' => ! $notification->read_at,
                                    'border-l-4 border-l-transparent' => (bool) $notification->read_at,
                                ])
                            >
                                <span class="mt-1.5 h-2.5 w-2.5 flex-shrink-0 rounded-full {{ $notification->read_at ? 'bg-slate-600' : 'bg-[#E42B35]' }}"></span>

                                <span class="min-w-0 flex-1">
                                    <span class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                                        <span class="font-rajdhani text-base font-bold uppercase tracking-wide text-white">
                                            {{ $notification->data['title'] ?? 'Notificación' }}
                                        </span>
                                        <span class="text-[11px] uppercase tracking-wider text-slate-500">
                                            {{ $notification->created_at?->diffForHumans() }}
                                        </span>
                                    </span>

                                    <span class="mt-2 block text-sm leading-6 text-slate-400">
                                        {{ \Illuminate\Support\Str::limit($notification->data['message'] ?? '', 140) }}
                                    </span>
                                </span>
                            </button>
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $notifications->links() }}
        </div>
    @endif

    @if ($showNotificationModal && $selectedNotification)
        <x-notification-modal :notification="$selectedNotification" />
    @endif
</div>
