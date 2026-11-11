@props(['notification'])

<div
    class="fixed inset-0 z-[100] flex items-center justify-center bg-black/70 p-4"
    wire:click.self="closeModal"
    x-on:keydown.escape.window="$wire.closeModal()"
    role="dialog"
    aria-modal="true"
>
    <div class="w-full max-w-lg rounded-sm border border-slate-700 bg-slate-800 shadow-2xl shadow-black/60">
        <div class="flex items-start justify-between border-b border-slate-700 px-6 py-4">
            <div>
                <p class="font-rajdhani text-xs font-semibold uppercase tracking-[0.3em] text-[#E42B35]">Notificación</p>
                <h2 class="mt-1 font-rajdhani text-xl font-bold uppercase tracking-wide text-white">
                    {{ $notification['title'] }}
                </h2>
            </div>

            <button
                type="button"
                wire:click="closeModal"
                class="rounded-sm p-1 text-slate-400 transition hover:bg-slate-700 hover:text-white"
                aria-label="Cerrar notificación"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="px-6 py-5">
            <p class="whitespace-pre-line text-sm leading-6 text-slate-300">{{ $notification['message'] }}</p>

            @if (! empty($notification['created_at_human']))
                <p class="mt-4 text-xs uppercase tracking-wider text-slate-500">
                    {{ $notification['created_at_human'] }}
                </p>
            @endif
        </div>

        <div class="flex flex-wrap justify-end gap-3 border-t border-slate-700 px-6 py-4">
            <button
                type="button"
                wire:click="closeModal"
                class="rounded-sm border border-slate-600 px-4 py-2 font-rajdhani text-xs font-bold uppercase tracking-wider text-slate-300 transition hover:bg-slate-700"
            >
                Cerrar
            </button>

            @if (! empty($notification['url']))
                <a
                    href="{{ $notification['url'] }}"
                    class="rounded-sm bg-[#E42B35] px-4 py-2 font-rajdhani text-xs font-bold uppercase tracking-wider text-white transition hover:bg-[#c9232c]"
                >
                    Ir al enlace
                </a>
            @endif
        </div>
    </div>
</div>
