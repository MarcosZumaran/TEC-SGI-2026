<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="overflow-hidden rounded-lg bg-white shadow">
        <!-- Encabezado y filtros -->
        <div class="border-b border-gray-200 p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Bandeja de Tickets</h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Consulta y da seguimiento a las incidencias registradas.
                    </p>
                </div>

                <a
                    href="{{ route('tickets.create') }}"
                    class="inline-flex flex-shrink-0 items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    + Nuevo Ticket
                </a>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-3">
                <div>
                    <label for="ticket-search" class="block text-sm font-medium text-gray-700">
                        Buscar
                    </label>
                    <input
                        id="ticket-search"
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="N° de ticket o descripción..."
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                </div>

                <div>
                    <label for="ticket-status" class="block text-sm font-medium text-gray-700">
                        Estado
                    </label>
                    <select
                        id="ticket-status"
                        wire:model.live="status"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="">Todos los estados</option>
                        <option value="Abierto">Abierto</option>
                        <option value="En Progreso">En Progreso</option>
                        <option value="En Espera">En Espera</option>
                        <option value="Resuelto">Resuelto</option>
                        <option value="Cerrado">Cerrado</option>
                    </select>
                </div>

                <div>
                    <label for="ticket-priority" class="block text-sm font-medium text-gray-700">
                        Prioridad
                    </label>
                    <select
                        id="ticket-priority"
                        wire:model.live="priority"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="">Todas las prioridades</option>
                        <option value="Baja">Baja</option>
                        <option value="Media">Media</option>
                        <option value="Alta">Alta</option>
                        <option value="Critica">Crítica</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Tabla -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Ticket
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Asunto
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Reportado por
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Prioridad
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Estado
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Técnico
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse ($tickets as $ticket)
                        <tr wire:key="ticket-{{ $ticket->id }}" class="transition-colors hover:bg-gray-100">
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold">
                                <a href="{{ route('tickets.show', $ticket->id) }}" class="text-indigo-600 hover:text-indigo-800">
                                    {{ $ticket->ticket_number }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="font-medium">{{ Str::limit($ticket->description, 50) }}</div>
                                <div class="mt-1 text-xs text-gray-500">
                                    {{ $ticket->category?->name ?? 'Sin categoría' }} | {{ $ticket->location?->name ?? 'Sin ubicación' }}
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                {{ $ticket->reporter?->name ?? 'Sin reportante' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                <span @class([
                                    'inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold',
                                    'bg-red-100 text-red-800' => $ticket->priority === 'Critica',
                                    'bg-orange-100 text-orange-800' => $ticket->priority === 'Alta',
                                    'bg-yellow-100 text-yellow-800' => $ticket->priority === 'Media',
                                    'bg-green-100 text-green-800' => $ticket->priority === 'Baja',
                                ])>
                                    {{ $ticket->priority }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                <span @class([
                                    'inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold',
                                    'bg-green-100 text-green-800' => $ticket->status === 'Abierto',
                                    'bg-blue-100 text-blue-800' => $ticket->status === 'En Progreso',
                                    'bg-yellow-100 text-yellow-800' => $ticket->status === 'En Espera',
                                    'bg-emerald-100 text-emerald-800' => $ticket->status === 'Resuelto',
                                    'bg-gray-100 text-gray-800' => $ticket->status === 'Cerrado',
                                ])>
                                    {{ $ticket->status }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                {{ $ticket->technician?->name ?? 'Sin asignar' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">
                                No se encontraron tickets con los filtros seleccionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="border-t border-gray-200 px-6 py-4">
            {{ $tickets->links() }}
        </div>
    </div>
</div>
