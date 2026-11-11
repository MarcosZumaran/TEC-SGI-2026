@php
    $uid = 'asset-form-' . ($assetId ?? 'create');
@endphp

<div
    x-data="{ open: false }"
    @if (! $assetId)
        x-on:open-asset-form.window="open = true"
    @endif
    x-on:asset-saved.window="open = false"
    x-on:keydown.escape.window="open = false"
>
    @if ($assetId)
        <button
            type="button"
            x-on:click="open = true"
            class="inline-flex items-center gap-1.5 rounded-lg border border-indigo-200 bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-700 transition hover:border-indigo-300 hover:bg-indigo-100"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Editar
        </button>
    @endif

    <template x-teleport="body">
        <div
            x-show="open"
            x-cloak
            x-transition.opacity
            class="fixed inset-0 z-50 overflow-y-auto"
            role="dialog"
            aria-modal="true"
            aria-labelledby="{{ $uid }}-title"
        >
            <div class="flex min-h-full items-center justify-center p-4 sm:p-6">
                <div
                    class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
                    x-on:click="open = false; $wire.resetValidation()"
                    aria-hidden="true"
                ></div>

                <div class="relative w-full max-w-4xl overflow-hidden rounded-2xl bg-white shadow-2xl">
                    <div class="flex items-start justify-between border-b border-gray-200 px-6 py-5">
                        <div>
                            <h2 id="{{ $uid }}-title" class="text-xl font-bold text-gray-900">
                                {{ $assetId ? 'Editar Equipo' : 'Registrar Nuevo Equipo' }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ $assetId ? 'Actualiza la información del activo y sus especificaciones.' : 'Completa los datos del nuevo activo del inventario.' }}
                            </p>
                        </div>

                        <button
                            type="button"
                            x-on:click="open = false; $wire.resetValidation()"
                            class="rounded-lg p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600"
                            aria-label="Cerrar"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form wire:submit="save" class="max-h-[calc(100vh-12rem)] overflow-y-auto">
                        <div class="space-y-6 p-6">
                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                <div>
                                    <label for="{{ $uid }}-asset-code" class="block text-sm font-medium text-gray-700">
                                        Código de Activo <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        id="{{ $uid }}-asset-code"
                                        type="text"
                                        wire:model="asset_code"
                                        required
                                        placeholder="Ej. PC-ADM-001"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                    @error('asset_code')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="{{ $uid }}-type" class="block text-sm font-medium text-gray-700">
                                        Tipo <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        id="{{ $uid }}-type"
                                        wire:model.live="type"
                                        required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                        @foreach ($types as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="{{ $uid }}-brand" class="block text-sm font-medium text-gray-700">
                                        Marca
                                    </label>
                                    <input
                                        id="{{ $uid }}-brand"
                                        type="text"
                                        wire:model="brand"
                                        placeholder="Ej. Dell"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                    @error('brand')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="{{ $uid }}-model" class="block text-sm font-medium text-gray-700">
                                        Modelo
                                    </label>
                                    <input
                                        id="{{ $uid }}-model"
                                        type="text"
                                        wire:model="model"
                                        placeholder="Ej. OptiPlex 7010"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                    @error('model')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="{{ $uid }}-serial-number" class="block text-sm font-medium text-gray-700">
                                        Número de Serie
                                    </label>
                                    <input
                                        id="{{ $uid }}-serial-number"
                                        type="text"
                                        wire:model="serial_number"
                                        placeholder="Ej. SN-123456"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                    @error('serial_number')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="{{ $uid }}-parent-id" class="block text-sm font-medium text-gray-700">
                                        Equipo Padre
                                    </label>
                                    <select
                                        id="{{ $uid }}-parent-id"
                                        wire:model="parent_id"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                        <option value="">Sin equipo padre</option>
                                        @foreach ($assets as $parentAsset)
                                            <option value="{{ $parentAsset->id }}">
                                                {{ $parentAsset->asset_code }} — {{ $parentAsset->brand ?: 'Sin marca' }} {{ $parentAsset->model }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('parent_id')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="{{ $uid }}-location-id" class="block text-sm font-medium text-gray-700">
                                        Ubicación <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        id="{{ $uid }}-location-id"
                                        wire:model="location_id"
                                        required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                        <option value="">Selecciona una ubicación</option>
                                        @foreach ($locations as $location)
                                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('location_id')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="{{ $uid }}-responsible-user-id" class="block text-sm font-medium text-gray-700">
                                        Responsable
                                    </label>
                                    <select
                                        id="{{ $uid }}-responsible-user-id"
                                        wire:model="responsible_user_id"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                        <option value="">Sin responsable</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('responsible_user_id')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="{{ $uid }}-status" class="block text-sm font-medium text-gray-700">
                                        Estado <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        id="{{ $uid }}-status"
                                        wire:model="status"
                                        required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                        @foreach ($statuses as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="{{ $uid }}-acquisition-date" class="block text-sm font-medium text-gray-700">
                                        Fecha de Adquisición
                                    </label>
                                    <input
                                        id="{{ $uid }}-acquisition-date"
                                        type="date"
                                        wire:model="acquisition_date"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                    @error('acquisition_date')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="{{ $uid }}-observations" class="block text-sm font-medium text-gray-700">
                                    Observaciones
                                </label>
                                <textarea
                                    id="{{ $uid }}-observations"
                                    wire:model="observations"
                                    rows="3"
                                    placeholder="Notas adicionales sobre el equipo..."
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                ></textarea>
                                @error('observations')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            @if (in_array($type, ['PC', 'LAP'], true))
                                <div class="rounded-xl border border-indigo-100 bg-indigo-50/50 p-5">
                                    <div class="mb-4">
                                        <h3 class="text-sm font-bold text-gray-800">Especificaciones Técnicas</h3>
                                        <p class="text-xs text-gray-500">
                                            Datos de hardware y red del equipo seleccionado.
                                        </p>
                                    </div>

                                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                        <div>
                                            <label for="{{ $uid }}-processor" class="block text-sm font-medium text-gray-700">
                                                Procesador
                                            </label>
                                            <input
                                                id="{{ $uid }}-processor"
                                                type="text"
                                                wire:model="processor"
                                                placeholder="Ej. Intel Core i5-12400"
                                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            >
                                            @error('processor')
                                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="{{ $uid }}-ram" class="block text-sm font-medium text-gray-700">
                                                RAM
                                            </label>
                                            <input
                                                id="{{ $uid }}-ram"
                                                type="text"
                                                wire:model="ram"
                                                placeholder="Ej. 16 GB"
                                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            >
                                            @error('ram')
                                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="{{ $uid }}-disk" class="block text-sm font-medium text-gray-700">
                                                Disco
                                            </label>
                                            <input
                                                id="{{ $uid }}-disk"
                                                type="text"
                                                wire:model="disk"
                                                placeholder="Ej. SSD 512 GB"
                                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            >
                                            @error('disk')
                                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="{{ $uid }}-os" class="block text-sm font-medium text-gray-700">
                                                Sistema Operativo
                                            </label>
                                            <input
                                                id="{{ $uid }}-os"
                                                type="text"
                                                wire:model="os"
                                                placeholder="Ej. Windows 11 Pro"
                                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            >
                                            @error('os')
                                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="{{ $uid }}-ip-address" class="block text-sm font-medium text-gray-700">
                                                Dirección IP
                                            </label>
                                            <input
                                                id="{{ $uid }}-ip-address"
                                                type="text"
                                                wire:model="ip_address"
                                                placeholder="Ej. 192.168.1.10"
                                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            >
                                            @error('ip_address')
                                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="{{ $uid }}-mac-address" class="block text-sm font-medium text-gray-700">
                                                Dirección MAC
                                            </label>
                                            <input
                                                id="{{ $uid }}-mac-address"
                                                type="text"
                                                wire:model="mac_address"
                                                placeholder="Ej. AA:BB:CC:DD:EE:FF"
                                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            >
                                            @error('mac_address')
                                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-col-reverse gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4 sm:flex-row sm:justify-end">
                            <button
                                type="button"
                                x-on:click="open = false; $wire.resetValidation()"
                                class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-100"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                wire:target="save"
                                class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-70"
                            >
                                <span wire:loading.remove wire:target="save">
                                    {{ $assetId ? 'Actualizar Equipo' : 'Guardar Equipo' }}
                                </span>
                                <span wire:loading wire:target="save">
                                    Guardando...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>
