<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    @if (session()->has('message'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-sm font-medium text-green-700" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
        <!-- Encabezado y acción -->
        <div class="border-b border-gray-200 bg-white p-6">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Inventario de Equipos</h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Consulta y filtra los equipos registrados en la organización.
                    </p>
                </div>

                @if (in_array(auth()->user()->role, ['admin', 'tecnico'], true))
                    <button
                        type="button"
                        x-data
                        x-on:click="$dispatch('open-asset-form')"
                        class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        + Nuevo Equipo
                    </button>
                @endif
            </div>

            <!-- Filtros -->
            <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-3">
                <div>
                    <label for="asset-search" class="block text-sm font-medium text-gray-700">
                        Buscar
                    </label>
                    <input
                        id="asset-search"
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Código, marca o serial..."
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                </div>

                <div>
                    <label for="asset-status" class="block text-sm font-medium text-gray-700">
                        Estado
                    </label>
                    <select
                        id="asset-status"
                        wire:model.live="status"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="">Todos los estados</option>
                        @foreach ($statuses as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="asset-type" class="block text-sm font-medium text-gray-700">
                        Tipo
                    </label>
                    <select
                        id="asset-type"
                        wire:model.live="type"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="">Todos los tipos</option>
                        @foreach ($types as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-4 flex justify-end">
                <button
                    type="button"
                    wire:click="clearFilters"
                    class="text-sm font-semibold text-indigo-600 transition hover:text-indigo-800"
                >
                    Limpiar filtros
                </button>
            </div>
        </div>

        <!-- Tabla -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Código de Activo
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Tipo
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Marca
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Modelo
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Ubicación
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Responsable
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Estado
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse ($assets as $asset)
                        <tr wire:key="asset-{{ $asset->id }}" class="transition hover:bg-gray-50">
                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                <div class="font-semibold text-indigo-700">{{ $asset->asset_code }}</div>
                                <div class="text-xs text-gray-500">
                                    Serial: {{ $asset->serial_number ?: '—' }}
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                {{ $types[$asset->type] ?? $asset->type }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                {{ $asset->brand ?: '—' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                {{ $asset->model ?: '—' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                {{ $asset->location?->name ?? 'Sin ubicación' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                {{ $asset->responsible?->name ?? 'Sin asignar' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                <span @class([
                                    'inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold',
                                    'bg-green-100 text-green-800' => $asset->status === 'Operativo',
                                    'bg-red-100 text-red-800' => $asset->status === 'Con Falla',
                                    'bg-yellow-100 text-yellow-800' => $asset->status === 'En Mantenimiento',
                                    'bg-gray-100 text-gray-800' => $asset->status === 'De Baja',
                                ])>
                                    {{ $asset->status }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                <livewire:asset-form :assetId="$asset->id" :key="'asset-form-'.$asset->id" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-sm text-gray-500">
                                No se encontraron equipos con los filtros seleccionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="border-t border-gray-200 px-6 py-4">
            {{ $assets->links() }}
        </div>
    </div>

    <livewire:asset-form />
</div>
