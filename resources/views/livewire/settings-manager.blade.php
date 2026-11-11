<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="mb-6">
        <p class="font-rajdhani text-xs font-semibold uppercase tracking-[0.35em] text-[#E42B35]">
            Panel de administración
        </p>
        <h1 class="mt-2 font-rajdhani text-3xl font-bold uppercase tracking-wider text-white">
            Configuración
        </h1>
        <p class="mt-2 max-w-3xl font-inter text-sm text-slate-400">
            Administra la identidad del sistema, los acuerdos de nivel de servicio y los módulos de configuración.
        </p>
    </div>

    <div class="mb-6 overflow-x-auto">
        <nav class="flex min-w-max gap-2 border-b border-slate-700 pb-3" aria-label="Secciones de configuración">
            @foreach ($tabs as $key => $label)
                <button
                    type="button"
                    wire:click="setTab('{{ $key }}')"
                    class="btn-anim rounded-sm px-4 py-2.5 font-rajdhani text-sm font-semibold uppercase tracking-wider transition-all duration-150 {{ $activeTab === $key ? 'bg-[#E42B35] text-white shadow-lg shadow-[#E42B35]/20' : 'bg-slate-700 text-slate-300 hover:bg-slate-600 hover:text-white' }}"
                >
                    {{ $label }}
                </button>
            @endforeach
        </nav>
    </div>

    <div>
        @if ($activeTab === 'general')
            <livewire:settings-general wire:key="settings-general" />
        @elseif ($activeTab === 'sla')
            <livewire:settings-sla wire:key="settings-sla" />
        @elseif ($activeTab === 'categories')
            <livewire:settings-categories wire:key="settings-categories" />
        @elseif ($activeTab === 'notifications')
            <livewire:settings-notifications wire:key="settings-notifications" />
        @elseif ($activeTab === 'security')
            <livewire:settings-security wire:key="settings-security" />
        @endif
    </div>
</div>
