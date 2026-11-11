<div class="space-y-6">
    @if (session('sla_message'))
        <div class="rounded-sm border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">
            {{ session('sla_message') }}
        </div>
    @endif

    <div class="rounded-sm border border-slate-700 bg-slate-800/95 shadow-xl shadow-black/40">
        <div class="border-b border-slate-700 px-6 py-5">
            <p class="font-rajdhani text-xs font-semibold uppercase tracking-[0.25em] text-[#E42B35]">
                Acuerdos de nivel de servicio
            </p>
            <h2 class="mt-1 font-rajdhani text-2xl font-bold uppercase tracking-wider text-white">
                SLA y Prioridades
            </h2>
            <p class="mt-2 font-inter text-sm text-slate-400">
                Define los tiempos máximos de respuesta y resolución para cada prioridad.
            </p>
        </div>

        <form wire:submit="savePolicies" class="space-y-6 p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-700 text-sm">
                    <thead class="bg-slate-900/70">
                        <tr>
                            <th class="px-4 py-3 text-left font-rajdhani text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Prioridad
                            </th>
                            <th class="px-4 py-3 text-left font-rajdhani text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Respuesta (horas)
                            </th>
                            <th class="px-4 py-3 text-left font-rajdhani text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Resolución (horas)
                            </th>
                            <th class="px-4 py-3 text-left font-rajdhani text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Activa
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                        @foreach ($policies as $index => $policy)
                            <tr>
                                <td class="whitespace-nowrap px-4 py-4">
                                    <span @class([
                                        'inline-flex rounded-sm px-2.5 py-1 font-rajdhani text-xs font-semibold uppercase tracking-wider',
                                        'bg-red-500/15 text-red-300' => $policy['priority'] === 'Critica',
                                        'bg-orange-500/15 text-orange-300' => $policy['priority'] === 'Alta',
                                        'bg-amber-500/15 text-amber-300' => $policy['priority'] === 'Media',
                                        'bg-emerald-500/15 text-emerald-300' => $policy['priority'] === 'Baja',
                                    ])>
                                        {{ $policy['priority'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <input
                                        type="number"
                                        min="0"
                                        max="9999"
                                        step="0.5"
                                        wire:model="policies.{{ $index }}.response_hours"
                                        class="block w-32 rounded-sm border-slate-600 bg-slate-900 text-white shadow-sm focus:border-[#E42B35] focus:ring-[#E42B35]"
                                    >
                                    @error('policies.'.$index.'.response_hours')
                                        <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                                    @enderror
                                </td>
                                <td class="px-4 py-4">
                                    <input
                                        type="number"
                                        min="0"
                                        max="9999"
                                        step="0.5"
                                        wire:model="policies.{{ $index }}.resolution_hours"
                                        class="block w-32 rounded-sm border-slate-600 bg-slate-900 text-white shadow-sm focus:border-[#E42B35] focus:ring-[#E42B35]"
                                    >
                                    @error('policies.'.$index.'.resolution_hours')
                                        <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                                    @enderror
                                </td>
                                <td class="px-4 py-4">
                                    <label class="inline-flex items-center gap-2 text-slate-300">
                                        <input
                                            type="checkbox"
                                            wire:model="policies.{{ $index }}.is_active"
                                            class="h-4 w-4 rounded-sm border-slate-600 bg-slate-900 text-[#E42B35] focus:ring-[#E42B35]"
                                        >
                                        <span class="text-xs uppercase tracking-wider">Activa</span>
                                    </label>
                                    @error('policies.'.$index.'.is_active')
                                        <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                                    @enderror
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end border-t border-slate-700 pt-6">
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="btn-anim inline-flex items-center rounded-sm bg-[#E42B35] px-5 py-2.5 font-rajdhani text-sm font-semibold uppercase tracking-wider text-white transition hover:bg-[#c9222c] disabled:cursor-not-allowed disabled:opacity-60"
                >
                    <span wire:loading.remove wire:target="savePolicies">Guardar políticas</span>
                    <span wire:loading wire:target="savePolicies">Guardando...</span>
                </button>
            </div>
        </form>
    </div>

    <div class="rounded-sm border border-slate-700 bg-slate-800/95 shadow-xl shadow-black/40">
        <div class="border-b border-slate-700 px-6 py-5">
            <p class="font-rajdhani text-xs font-semibold uppercase tracking-[0.25em] text-[#E42B35]">
                Calendario laboral
            </p>
            <h2 class="mt-1 font-rajdhani text-2xl font-bold uppercase tracking-wider text-white">
                Horario de Servicio
            </h2>
            <p class="mt-2 font-inter text-sm text-slate-400">
                Los horarios activos se utilizan para calcular el vencimiento SLA de los tickets nuevos.
            </p>
        </div>

        <form wire:submit="saveBusinessHours" class="space-y-6 p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-700 text-sm">
                    <thead class="bg-slate-900/70">
                        <tr>
                            <th class="px-4 py-3 text-left font-rajdhani text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Día
                            </th>
                            <th class="px-4 py-3 text-left font-rajdhani text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Inicio
                            </th>
                            <th class="px-4 py-3 text-left font-rajdhani text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Fin
                            </th>
                            <th class="px-4 py-3 text-left font-rajdhani text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Activo
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                        @foreach ($businessHours as $index => $hour)
                            <tr>
                                <td class="whitespace-nowrap px-4 py-4 font-medium text-slate-200">
                                    {{ $hour['label'] }}
                                </td>
                                <td class="px-4 py-4">
                                    <input
                                        type="time"
                                        wire:model="businessHours.{{ $index }}.start_time"
                                        class="block w-36 rounded-sm border-slate-600 bg-slate-900 text-white shadow-sm focus:border-[#E42B35] focus:ring-[#E42B35]"
                                    >
                                    @error('businessHours.'.$index.'.start_time')
                                        <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                                    @enderror
                                </td>
                                <td class="px-4 py-4">
                                    <input
                                        type="time"
                                        wire:model="businessHours.{{ $index }}.end_time"
                                        class="block w-36 rounded-sm border-slate-600 bg-slate-900 text-white shadow-sm focus:border-[#E42B35] focus:ring-[#E42B35]"
                                    >
                                    @error('businessHours.'.$index.'.end_time')
                                        <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                                    @enderror
                                </td>
                                <td class="px-4 py-4">
                                    <label class="inline-flex items-center gap-2 text-slate-300">
                                        <input
                                            type="checkbox"
                                            wire:model="businessHours.{{ $index }}.is_active"
                                            class="h-4 w-4 rounded-sm border-slate-600 bg-slate-900 text-[#E42B35] focus:ring-[#E42B35]"
                                        >
                                        <span class="text-xs uppercase tracking-wider">Activo</span>
                                    </label>
                                    @error('businessHours.'.$index.'.is_active')
                                        <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                                    @enderror
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end border-t border-slate-700 pt-6">
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="btn-anim inline-flex items-center rounded-sm bg-[#284E8C] px-5 py-2.5 font-rajdhani text-sm font-semibold uppercase tracking-wider text-white transition hover:bg-[#1f3d6e] disabled:cursor-not-allowed disabled:opacity-60"
                >
                    <span wire:loading.remove wire:target="saveBusinessHours">Guardar horarios</span>
                    <span wire:loading wire:target="saveBusinessHours">Guardando...</span>
                </button>
            </div>
        </form>
    </div>
</div>
