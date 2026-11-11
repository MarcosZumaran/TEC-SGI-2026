<div class="relative" x-data="{ open: false }">
    <button
        type="button"
        @click="open = !open"
        class="relative rounded-sm p-2 text-slate-200 transition hover:bg-white/10 hover:text-white"
        aria-label="Notificaciones"
    >
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>

        @if ($unreadCount > 0)
            <span class="absolute -right-1 -top-1 flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-[#E42B35] px-1 text-[10px] font-bold text-white">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    <div
        x-show="open"
        x-cloak
        @click.outside="open = false"
        class="absolute right-0 z-50 mt-3 w-80 max-w-[calc(100vw-2rem)] origin-top-right rounded-sm border border-slate-700 bg-slate-800 shadow-2xl shadow-black/60 sm:w-96"
    >
        <div class="flex items-center justify-between border-b border-slate-700 px-4 py-3">
            <div>
                <p class="font-rajdhani text-sm font-bold uppercase tracking-wider text-white">Notificaciones</p>
                <p class="text-[11px] text-slate-400">{{ $unreadCount }} sin leer</p>
            </div>

            @if ($unreadCount > 0)
                <button
                    type="button"
                    wire:click="markAllAsRead"
                    class="font-rajdhani text-[11px] font-semibold uppercase tracking-wider text-amber-400 transition hover:text-amber-300"
                >
                    Marcar todas
                </button>
            @endif
        </div>

        <div class="max-h-96 divide-y divide-slate-700/70 overflow-y-auto">
            @forelse ($this->notifications as $notification)
                <button
                    type="button"
                    wire:key="notification-{{ $notification->id }}"
                    wire:click="markAsRead('{{ $notification->id }}')"
                    @click="open = false"
                    class="flex w-full items-start gap-3 px-4 py-3 text-left transition hover:bg-slate-700/60"
                >
                    <span class="mt-1.5 h-2 w-2 flex-shrink-0 rounded-full {{ $notification->read_at ? 'bg-slate-600' : 'bg-[#E42B35]' }}"></span>

                    <span class="min-w-0 flex-1">
                        <span class="block font-rajdhani text-sm font-semibold uppercase tracking-wide text-white">
                            {{ $notification->data['title'] ?? 'Notificación' }}
                        </span>
                        <span class="mt-1 block text-xs leading-5 text-slate-400">
                            {{ \Illuminate\Support\Str::limit($notification->data['message'] ?? '', 80) }}
                        </span>
                        <span class="mt-1 block text-[11px] uppercase tracking-wider text-slate-500">
                            {{ $notification->created_at?->diffForHumans() }}
                        </span>
                    </span>
                </button>
            @empty
                <div class="px-4 py-8 text-center">
                    <p class="font-rajdhani text-sm uppercase tracking-wider text-slate-400">No tienes notificaciones</p>
                    <p class="mt-1 text-xs text-slate-500">Aquí aparecerán las actualizaciones de tus tickets.</p>
                </div>
            @endforelse
        </div>

        <div class="border-t border-slate-700 p-3">
            <a
                href="{{ route('notifications.index') }}"
                class="block rounded-sm bg-[#284E8C] px-4 py-2 text-center font-rajdhani text-xs font-bold uppercase tracking-wider text-white transition hover:bg-[#1f3d70]"
            >
                Mostrar todas las notificaciones
            </a>
        </div>
    </div>

    @if ($showNotificationModal && $selectedNotification)
        <x-notification-modal :notification="$selectedNotification" />
    @endif
</div>
