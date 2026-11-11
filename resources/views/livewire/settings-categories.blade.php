<div class="space-y-6">
    @if (session('categories_message'))
        <div class="rounded-sm border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-sm font-medium text-emerald-300">
            {{ session('categories_message') }}
        </div>
    @endif

    <div class="grid gap-6 xl:grid-cols-2">
        <div class="rounded-sm border border-slate-700 bg-slate-800/95 shadow-xl shadow-black/40">
            <div class="flex flex-col gap-3 border-b border-slate-700 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="font-rajdhani text-xs font-semibold uppercase tracking-[0.3em] text-[#E42B35]">Organización</p>
                    <h2 class="mt-1 font-rajdhani text-2xl font-bold uppercase tracking-wider text-white">Departamentos</h2>
                    <p class="mt-1 text-sm text-slate-400">Áreas responsables de atender las categorías.</p>
                </div>
                <button
                    type="button"
                    wire:click="createDepartment"
                    class="btn-anim inline-flex items-center rounded-sm bg-[#284E8C] px-4 py-2 font-rajdhani text-xs font-bold uppercase tracking-wider text-white shadow-lg shadow-[#284E8C]/20 transition hover:bg-[#1f3f73]"
                >
                    + Nuevo Departamento
                </button>
            </div>

            @if ($showDepartmentForm)
                <form wire:submit="saveDepartment" class="space-y-4 border-b border-slate-700 bg-slate-900/60 p-6">
                    <h3 class="font-rajdhani text-lg font-bold uppercase tracking-wider text-white">
                        {{ $editingDepartmentId ? 'Editar departamento' : 'Nuevo departamento' }}
                    </h3>

                    <div>
                        <label for="department-name" class="mb-1 block font-rajdhani text-xs font-semibold uppercase tracking-wider text-slate-300">Nombre</label>
                        <input
                            id="department-name"
                            type="text"
                            wire:model="departmentName"
                            class="w-full rounded-sm border border-slate-600 bg-slate-900 px-3 py-2 text-sm text-white placeholder-slate-500 focus:border-[#E42B35] focus:outline-none focus:ring-2 focus:ring-[#E42B35]/30"
                            placeholder="Ej. Soporte técnico"
                        >
                        @error('departmentName')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="department-description" class="mb-1 block font-rajdhani text-xs font-semibold uppercase tracking-wider text-slate-300">Descripción</label>
                        <input
                            id="department-description"
                            type="text"
                            wire:model="departmentDescription"
                            class="w-full rounded-sm border border-slate-600 bg-slate-900 px-3 py-2 text-sm text-white placeholder-slate-500 focus:border-[#E42B35] focus:outline-none focus:ring-2 focus:ring-[#E42B35]/30"
                            placeholder="Descripción opcional"
                        >
                        @error('departmentDescription')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-2">
                        <button
                            type="button"
                            wire:click="cancelDepartmentForm"
                            class="rounded-sm border border-slate-600 px-4 py-2 font-rajdhani text-xs font-bold uppercase tracking-wider text-slate-300 transition hover:bg-slate-700"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            class="btn-anim rounded-sm bg-[#E42B35] px-4 py-2 font-rajdhani text-xs font-bold uppercase tracking-wider text-white shadow-lg shadow-[#E42B35]/20 transition hover:bg-[#c9232c]"
                        >
                            Guardar
                        </button>
                    </div>
                </form>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-700">
                    <thead class="bg-slate-900/80">
                        <tr>
                            <th class="px-4 py-3 text-left font-rajdhani text-xs font-semibold uppercase tracking-wider text-slate-300">Departamento</th>
                            <th class="px-4 py-3 text-left font-rajdhani text-xs font-semibold uppercase tracking-wider text-slate-300">Descripción</th>
                            <th class="px-4 py-3 text-center font-rajdhani text-xs font-semibold uppercase tracking-wider text-slate-300">Categorías</th>
                            <th class="px-4 py-3 text-right font-rajdhani text-xs font-semibold uppercase tracking-wider text-slate-300">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/70">
                        @forelse ($departments as $department)
                            <tr wire:key="department-{{ $department['id'] }}">
                                <td class="whitespace-nowrap px-4 py-3 text-sm font-semibold text-white">{{ $department['name'] }}</td>
                                <td class="px-4 py-3 text-sm text-slate-400">{{ $department['description'] ?: 'Sin descripción' }}</td>
                                <td class="px-4 py-3 text-center text-sm text-slate-300">{{ $department['categories_count'] }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-right">
                                    <button type="button" wire:click="editDepartment({{ $department['id'] }})" class="font-rajdhani text-xs font-semibold uppercase tracking-wider text-blue-300 hover:text-blue-200">
                                        Editar
                                    </button>
                                    <button
                                        type="button"
                                        wire:click="deleteDepartment({{ $department['id'] }})"
                                        wire:confirm="¿Eliminar este departamento? Las categorías quedarán sin departamento."
                                        class="ml-3 font-rajdhani text-xs font-semibold uppercase tracking-wider text-red-300 hover:text-red-200"
                                    >
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-sm text-slate-400">No hay departamentos registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-sm border border-slate-700 bg-slate-800/95 shadow-xl shadow-black/40">
            <div class="border-b border-slate-700 px-6 py-5">
                <p class="font-rajdhani text-xs font-semibold uppercase tracking-[0.3em] text-[#E42B35]">Clasificación</p>
                <h2 class="mt-1 font-rajdhani text-2xl font-bold uppercase tracking-wider text-white">Categorías</h2>
                <p class="mt-1 text-sm text-slate-400">Asigna cada categoría al departamento que la atiende.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-700">
                    <thead class="bg-slate-900/80">
                        <tr>
                            <th class="px-4 py-3 text-left font-rajdhani text-xs font-semibold uppercase tracking-wider text-slate-300">Categoría</th>
                            <th class="px-4 py-3 text-left font-rajdhani text-xs font-semibold uppercase tracking-wider text-slate-300">Departamento</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/70">
                        @forelse ($categories as $category)
                            <tr wire:key="category-{{ $category['id'] }}">
                                <td class="px-4 py-3 text-sm font-semibold text-white">{{ $category['name'] }}</td>
                                <td class="px-4 py-3">
                                    <select
                                        wire:change="updateCategoryDepartment({{ $category['id'] }}, $event.target.value)"
                                        class="w-full rounded-sm border border-slate-600 bg-slate-900 px-3 py-2 text-sm text-white focus:border-[#E42B35] focus:outline-none focus:ring-2 focus:ring-[#E42B35]/30"
                                    >
                                        <option value="">Sin departamento</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department['id'] }}" @selected((string) $category['department_id'] === (string) $department['id'])>
                                                {{ $department['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-4 py-8 text-center text-sm text-slate-400">No hay categorías registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
