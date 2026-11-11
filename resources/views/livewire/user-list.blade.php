<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="overflow-hidden rounded-lg bg-white shadow">
        <!-- Encabezado -->
        <div class="border-b border-gray-200 p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Gestión de Usuarios</h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Administra las cuentas y roles del sistema.
                    </p>
                </div>

                <button
                    type="button"
                    x-data
                    x-on:click="$dispatch('open-user-form')"
                    class="inline-flex flex-shrink-0 items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    + Nuevo Usuario
                </button>
            </div>

            <div class="mt-6 max-w-md">
                <label for="user-search" class="block text-sm font-medium text-gray-700">
                    Buscar
                </label>
                <input
                    id="user-search"
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Buscar por nombre o correo electrónico..."
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
            </div>
        </div>

        @if (session('message'))
            <div class="border-b border-green-200 bg-green-50 px-6 py-3 text-sm font-medium text-green-800">
                {{ session('message') }}
            </div>
        @endif

        <!-- Tabla -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Nombre
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Correo
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Rol
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Fecha de registro
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse ($users as $user)
                        <tr wire:key="user-{{ $user->id }}" class="transition-colors hover:bg-gray-100">
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                {{ $user->name }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                {{ $user->email }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                <span @class([
                                    'inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold',
                                    'bg-red-100 text-red-800' => $user->role === 'admin',
                                    'bg-blue-100 text-blue-800' => $user->role === 'tecnico',
                                    'bg-purple-100 text-purple-800' => $user->role === 'directivo',
                                    'bg-gray-100 text-gray-800' => $user->role === 'usuario',
                                ])>
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                {{ $user->created_at?->format('d/m/Y H:i') ?? '—' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                <livewire:user-form :userId="$user->id" :key="'user-form-' . $user->id" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                                No se encontraron usuarios con los filtros seleccionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="border-t border-gray-200 px-6 py-4">
            {{ $users->links() }}
        </div>
    </div>

    <livewire:user-form />
</div>
