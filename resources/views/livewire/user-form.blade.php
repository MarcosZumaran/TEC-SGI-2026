<div
    x-data="{ open: false }"
    @if (! $userId)
        x-on:open-user-form.window="open = true"
    @endif
    x-on:user-saved.window="open = false"
    x-on:keydown.escape.window="open = false"
>
    @if ($userId)
        <button
            type="button"
            x-on:click="open = true"
            class="inline-flex items-center justify-center rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-700 transition hover:bg-indigo-100"
        >
            Editar
        </button>
    @endif

    <template x-teleport="body">
        <div
            x-show="open"
            x-cloak
            x-transition.opacity
            class="fixed inset-0 z-[60] flex items-center justify-center px-4 py-6 sm:px-6"
            role="dialog"
            aria-modal="true"
        >
            <div class="absolute inset-0 bg-slate-900/70" x-on:click="open = false"></div>

            <div class="relative max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-lg bg-white shadow-xl" x-on:click.stop>
                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                    <h2 class="text-lg font-bold text-gray-800">
                        {{ $userId ? 'Editar Usuario' : 'Nuevo Usuario' }}
                    </h2>

                    <button
                        type="button"
                        x-on:click="open = false"
                        class="rounded-lg p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600"
                        aria-label="Cerrar"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit="save" class="space-y-5 p-6">
                    <div>
                        <label for="user-name-{{ $userId ?? 'new' }}" class="block text-sm font-medium text-gray-700">
                            Nombre completo
                        </label>
                        <input
                            id="user-name-{{ $userId ?? 'new' }}"
                            type="text"
                            wire:model="name"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            autocomplete="name"
                        >
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="user-email-{{ $userId ?? 'new' }}" class="block text-sm font-medium text-gray-700">
                            Correo electrónico
                        </label>
                        <input
                            id="user-email-{{ $userId ?? 'new' }}"
                            type="email"
                            wire:model="email"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            autocomplete="email"
                        >
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="user-password-{{ $userId ?? 'new' }}" class="block text-sm font-medium text-gray-700">
                            {{ $userId ? 'Contraseña (dejar en blanco para no cambiar)' : 'Contraseña' }}
                        </label>
                        <input
                            id="user-password-{{ $userId ?? 'new' }}"
                            type="password"
                            wire:model="password"
                            placeholder="{{ $userId ? 'Dejar en blanco para no cambiar' : 'Mínimo 8 caracteres' }}"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            autocomplete="new-password"
                        >
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="user-role-{{ $userId ?? 'new' }}" class="block text-sm font-medium text-gray-700">
                            Rol
                        </label>
                        <select
                            id="user-role-{{ $userId ?? 'new' }}"
                            wire:model="role"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            @foreach ($roles as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3 border-t border-gray-200 pt-5">
                        <button
                            type="button"
                            x-on:click="open = false"
                            class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50"
                        >
                            Cancelar
                        </button>

                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            wire:target="save"
                            class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>
