<div class="rounded-sm border border-slate-700 bg-slate-800/95 shadow-xl shadow-black/40">
    <div class="border-b border-slate-700 px-6 py-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="font-rajdhani text-xs font-semibold uppercase tracking-[0.3em] text-[#E42B35]">Configuración</p>
                <h2 class="mt-1 font-rajdhani text-2xl font-bold uppercase tracking-wider text-white">Permisos por rol</h2>
                <p class="mt-1 text-sm text-slate-400">Define qué acciones puede realizar cada rol dentro del sistema.</p>
            </div>
            <span class="w-fit rounded-sm border border-[#284E8C] bg-[#284E8C]/20 px-3 py-1 font-rajdhani text-xs font-semibold uppercase tracking-wider text-blue-200">
                Solo administradores
            </span>
        </div>
    </div>

    <form wire:submit="save" class="space-y-6 p-6">
        @if (session('security_message'))
            <div class="rounded-sm border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-sm font-medium text-emerald-300">
                {{ session('security_message') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-700">
                <thead class="bg-slate-900/80">
                    <tr>
                        <th class="px-3 py-3 text-left font-rajdhani text-xs font-semibold uppercase tracking-wider text-slate-300">Rol</th>
                        @foreach ($permissions as $permission)
                            <th class="px-3 py-3 text-center font-rajdhani text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                                {{ $permission }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/70">
                    @foreach ($roles as $role)
                        <tr wire:key="role-{{ $role['name'] }}">
                            <td class="whitespace-nowrap px-3 py-4">
                                <span @class([
                                    'rounded-sm px-2.5 py-1 font-rajdhani text-xs font-bold uppercase tracking-wider',
                                    'bg-[#E42B35]/20 text-red-300' => $role['name'] === 'admin',
                                    'bg-[#284E8C]/30 text-blue-200' => $role['name'] === 'tecnico',
                                    'bg-purple-500/20 text-purple-200' => $role['name'] === 'directivo',
                                    'bg-slate-600/40 text-slate-300' => $role['name'] === 'usuario',
                                ])>
                                    {{ $role['name'] }}
                                </span>
                            </td>
                            @foreach ($permissions as $permission)
                                <td class="px-3 py-4 text-center">
                                    <input
                                        type="checkbox"
                                        value="{{ $permission }}"
                                        wire:model="selectedPermissions.{{ $role['name'] }}"
                                        class="h-4 w-4 rounded border-slate-600 bg-slate-900 text-[#E42B35] focus:ring-2 focus:ring-[#E42B35]/40"
                                    >
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @error('selectedPermissions')
            <p class="text-xs text-red-400">{{ $message }}</p>
        @enderror

        <div class="flex justify-end border-t border-slate-700 pt-6">
            <button
                type="submit"
                class="btn-anim inline-flex items-center rounded-sm bg-[#E42B35] px-5 py-2.5 font-rajdhani text-sm font-bold uppercase tracking-wider text-white shadow-lg shadow-[#E42B35]/20 transition hover:bg-[#c9232c] disabled:cursor-not-allowed disabled:opacity-60"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove wire:target="save">Guardar permisos</span>
                <span wire:loading wire:target="save">Guardando...</span>
            </button>
        </div>
    </form>
</div>
