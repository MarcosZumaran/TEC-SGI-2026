<div class="rounded-sm border border-slate-700 bg-slate-800/95 shadow-xl shadow-black/40">
    <div class="border-b border-slate-700 px-6 py-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="font-rajdhani text-xs font-semibold uppercase tracking-[0.25em] text-[#E42B35]">
                    Configuración general
                </p>
                <h2 class="mt-1 font-rajdhani text-2xl font-bold uppercase tracking-wider text-white">
                    Identidad y operación
                </h2>
            </div>
            <span class="inline-flex w-fit items-center rounded-sm border border-[#284E8C] bg-[#284E8C]/30 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-blue-100">
                Solo administradores
            </span>
        </div>
    </div>

    <form wire:submit="save" class="space-y-6 p-6">
        @if (session('message'))
            <div class="rounded-sm border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">
                {{ session('message') }}
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-2">
            <div>
                <label for="site-name" class="block font-rajdhani text-sm font-semibold uppercase tracking-wider text-slate-300">
                    Nombre del sitio
                </label>
                <input
                    id="site-name"
                    type="text"
                    wire:model="site_name"
                    class="mt-2 block w-full rounded-sm border-slate-600 bg-slate-900 text-white shadow-sm placeholder:text-slate-500 focus:border-[#E42B35] focus:ring-[#E42B35]"
                >
                @error('site_name')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
                <p class="mt-2 font-inter text-xs text-slate-500">
                    Se muestra en el título de la página y en el encabezado principal.
                </p>
            </div>

            <div>
                <label for="items-per-page" class="block font-rajdhani text-sm font-semibold uppercase tracking-wider text-slate-300">
                    Elementos por página
                </label>
                <input
                    id="items-per-page"
                    type="number"
                    min="1"
                    max="100"
                    wire:model="items_per_page"
                    class="mt-2 block w-full rounded-sm border-slate-600 bg-slate-900 text-white shadow-sm focus:border-[#E42B35] focus:ring-[#E42B35]"
                >
                @error('items_per_page')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
                <p class="mt-2 font-inter text-xs text-slate-500">
                    Define la cantidad de registros visible en las listas del sistema.
                </p>
            </div>
        </div>

        <div class="border-t border-slate-700 pt-6">
            <label class="flex cursor-pointer items-start gap-4 rounded-sm border border-slate-700 bg-slate-900/60 p-4 transition hover:border-slate-600">
                <input
                    type="checkbox"
                    wire:model="maintenance_mode"
                    class="mt-1 h-5 w-5 rounded-sm border-slate-600 bg-slate-900 text-[#E42B35] focus:ring-[#E42B35]"
                >
                <span>
                    <span class="block font-rajdhani text-sm font-semibold uppercase tracking-wider text-slate-200">
                        Modo mantenimiento
                    </span>
                    <span class="mt-1 block font-inter text-xs text-slate-500">
                        Muestra un aviso global cuando el sistema se encuentre en mantenimiento.
                    </span>
                </span>
            </label>
            @error('maintenance_mode')
                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end border-t border-slate-700 pt-6">
            <button
                type="submit"
                wire:loading.attr="disabled"
                class="btn-anim inline-flex items-center rounded-sm bg-[#E42B35] px-5 py-2.5 font-rajdhani text-sm font-semibold uppercase tracking-wider text-white transition hover:bg-[#c9222c] disabled:cursor-not-allowed disabled:opacity-60"
            >
                <span wire:loading.remove>Guardar configuración</span>
                <span wire:loading>Guardando...</span>
            </button>
        </div>
    </form>
</div>
