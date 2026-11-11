<div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="font-rajdhani text-xs font-semibold uppercase tracking-[0.25em] text-amber-400">
                Panel de administración
            </p>
            <h1 class="mt-2 font-rajdhani text-3xl font-bold uppercase tracking-wider text-white sm:text-4xl">
                Configuración
            </h1>
            <p class="mt-2 max-w-2xl font-inter text-sm text-slate-400">
                Administra los parámetros generales de TEC-SGI 2026.
            </p>
        </div>

        <span class="inline-flex w-fit items-center gap-2 rounded-sm border border-[#E42B35]/40 bg-[#E42B35]/10 px-3 py-1.5 font-rajdhani text-xs font-semibold uppercase tracking-wider text-red-300">
            <span class="h-1.5 w-1.5 bg-[#E42B35]"></span>
            Solo administradores
        </span>
    </div>

    @if (session('message'))
        <div class="mb-6 flex items-start gap-3 rounded-sm border border-emerald-500/30 bg-emerald-500/10 p-4 text-sm text-emerald-200">
            <span class="mt-1 h-2 w-2 flex-shrink-0 bg-emerald-400"></span>
            <span>{{ session('message') }}</span>
        </div>
    @endif

    <form wire:submit="save" class="space-y-6">
        <section class="rounded-sm border border-slate-700 bg-slate-800/90 p-6 shadow-xl shadow-black/40">
            <div class="mb-6 flex items-start gap-3 border-b border-slate-700 pb-4">
                <span class="mt-1.5 h-2 w-2 flex-shrink-0 bg-[#E42B35]"></span>
                <div>
                    <h2 class="font-rajdhani text-lg font-bold uppercase tracking-wider text-white">
                        Identidad del sistema
                    </h2>
                    <p class="mt-1 font-inter text-sm text-slate-400">
                        Define el nombre visible y la cantidad de registros por página.
                    </p>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="site_name" class="block font-rajdhani text-sm font-semibold uppercase tracking-wider text-blue-100">
                        Nombre del sitio
                    </label>
                    <input
                        id="site_name"
                        type="text"
                        wire:model="site_name"
                        class="mt-2 block w-full rounded-sm border-slate-600 bg-slate-900 px-4 py-2.5 font-inter text-sm text-white placeholder-slate-500 shadow-sm focus:border-[#E42B35] focus:ring-[#E42B35]"
                        placeholder="TEC-SGI 2026"
                    >
                    <p class="mt-2 font-inter text-xs text-slate-500">
                        Se muestra en el título y en el encabezado del sistema.
                    </p>
                    @error('site_name')
                        <p class="mt-1 font-inter text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="items_per_page" class="block font-rajdhani text-sm font-semibold uppercase tracking-wider text-blue-100">
                        Elementos por página
                    </label>
                    <input
                        id="items_per_page"
                        type="number"
                        min="1"
                        max="100"
                        wire:model="items_per_page"
                        class="mt-2 block w-full rounded-sm border-slate-600 bg-slate-900 px-4 py-2.5 font-inter text-sm text-white placeholder-slate-500 shadow-sm focus:border-[#E42B35] focus:ring-[#E42B35]"
                    >
                    <p class="mt-2 font-inter text-xs text-slate-500">
                        Valor entre 1 y 100 registros por página.
                    </p>
                    @error('items_per_page')
                        <p class="mt-1 font-inter text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </section>

        <section class="rounded-sm border border-slate-700 bg-slate-800/90 p-6 shadow-xl shadow-black/40">
            <div class="mb-6 flex items-start gap-3 border-b border-slate-700 pb-4">
                <span class="mt-1.5 h-2 w-2 flex-shrink-0 bg-[#E42B35]"></span>
                <div>
                    <h2 class="font-rajdhani text-lg font-bold uppercase tracking-wider text-white">
                        Operación
                    </h2>
                    <p class="mt-1 font-inter text-sm text-slate-400">
                        Controla los avisos generales del sistema.
                    </p>
                </div>
            </div>

            <label for="maintenance_mode" class="flex cursor-pointer items-start gap-4 rounded-sm border border-slate-700 bg-slate-900/70 p-4 transition hover:border-slate-600">
                <input
                    id="maintenance_mode"
                    type="checkbox"
                    wire:model="maintenance_mode"
                    class="mt-0.5 h-5 w-5 rounded-sm border-slate-600 bg-slate-900 text-[#E42B35] focus:ring-[#E42B35] focus:ring-offset-slate-900"
                >
                <span class="min-w-0">
                    <span class="block font-rajdhani text-sm font-semibold uppercase tracking-wider text-white">
                        Modo mantenimiento
                    </span>
                    <span class="mt-1 block font-inter text-sm text-slate-400">
                        Muestra un aviso de mantenimiento en la parte superior del sistema.
                    </span>
                </span>
            </label>
            @error('maintenance_mode')
                <p class="mt-2 font-inter text-sm text-red-400">{{ $message }}</p>
            @enderror
        </section>

        <div class="flex flex-col-reverse gap-4 sm:flex-row sm:items-center sm:justify-between">
            <p class="font-inter text-xs text-slate-500">
                Los cambios se guardan en la configuración del sistema.
            </p>

            <button
                type="submit"
                wire:loading.attr="disabled"
                wire:target="save"
                class="btn-anim inline-flex items-center justify-center rounded-sm bg-[#E42B35] px-6 py-3 font-rajdhani text-sm font-bold uppercase tracking-wider text-white shadow-lg shadow-black/30 transition hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-[#E42B35] focus:ring-offset-2 focus:ring-offset-slate-900 disabled:cursor-not-allowed disabled:opacity-70"
            >
                <span wire:loading.remove wire:target="save">Guardar Configuración</span>
                <span wire:loading wire:target="save">Guardando...</span>
            </button>
        </div>
    </form>
</div>
