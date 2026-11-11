<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Ticket {{ $ticket->ticket_number }}</h2>
                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                    {{ $ticket->status }}
                </span>
            </div>
            <a href="{{ route('tickets.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                Volver a la bandeja de tickets
            </a>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-gray-50 rounded-lg p-5">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Información del Ticket</h3>

                    <dl class="space-y-3">
                        <div class="flex justify-between gap-4">
                            <dt class="text-sm font-medium text-gray-500">Número</dt>
                            <dd class="text-sm font-semibold text-gray-900">{{ $ticket->ticket_number }}</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-sm font-medium text-gray-500">Estado</dt>
                            <dd class="text-sm font-semibold text-gray-900">{{ $ticket->status }}</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-sm font-medium text-gray-500">Prioridad</dt>
                            <dd class="text-sm font-semibold text-gray-900">{{ $ticket->priority }}</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-sm font-medium text-gray-500">Reportado por</dt>
                            <dd class="text-sm font-semibold text-gray-900">{{ $ticket->reporter?->name }}</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-sm font-medium text-gray-500">Técnico asignado</dt>
                            <dd class="text-sm font-semibold text-gray-900">{{ $ticket->technician?->name ?? 'Sin asignar' }}</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-sm font-medium text-gray-500">Equipo afectado</dt>
                            <dd class="text-sm font-semibold text-gray-900">
                                {{ $ticket->asset ? $ticket->asset->asset_code : 'No aplica' }}
                            </dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-sm font-medium text-gray-500">Ubicación</dt>
                            <dd class="text-sm font-semibold text-gray-900">{{ $ticket->location?->name }}</dd>
                        </div>
                    </dl>

                    <div class="mt-6">
                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Descripción</h4>
                        <p class="text-sm text-gray-900">{{ $ticket->description }}</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white border border-gray-200 rounded-lg p-5">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Acciones</h3>

                        @if(auth()->user()->role === 'tecnico' || auth()->user()->role === 'admin')
                            @if(is_null($ticket->technician_id))
                                <button
                                    type="button"
                                    wire:click="assignToMe"
                                    class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition"
                                >
                                    Asignarme este ticket
                                </button>
                            @endif
                        @endif

                        @if(auth()->user()->id === $ticket->technician_id)
                            <form wire:submit="changeStatus($newStatus)" class="space-y-3">
                                <div>
                                    <label for="ticket-status" class="block text-sm font-medium text-gray-700">Estado</label>
                                    <select
                                        wire:model="newStatus"
                                        id="ticket-status"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                        <option value="En Progreso">En Progreso</option>
                                        <option value="En Espera">En Espera</option>
                                        <option value="Resuelto">Resuelto</option>
                                    </select>
                                </div>

                                <button
                                    type="submit"
                                    class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition"
                                >
                                    Cambiar estado
                                </button>
                            </form>
                        @endif
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-5">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Agregar Comentario</h3>

                        <form wire:submit="addComment($message, $isInternal)" class="space-y-4">
                            <div>
                                <label for="ticket-comment" class="block text-sm font-medium text-gray-700">Mensaje</label>
                                <textarea
                                    wire:model="message"
                                    id="ticket-comment"
                                    rows="4"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                ></textarea>
                            </div>

                            @if(auth()->user()->role === 'tecnico' || auth()->user()->role === 'admin')
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" wire:model="isInternal" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                                    <span class="text-sm text-gray-700">Nota interna</span>
                                </label>
                            @endif

                            <button
                                type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition"
                            >
                                Agregar comentario
                            </button>
                        </form>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-5">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Historial de Comentarios</h3>

                        @forelse(($ticket->comments ?? collect()) as $comment)
                            <div class="border-l-2 border-gray-200 pl-4 mb-4">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                    <span class="text-sm font-semibold text-gray-800">{{ $comment->user?->name }}</span>
                                    <span class="text-xs text-gray-500">{{ $comment->created_at }}</span>
                                </div>
                                <p class="mt-2 text-sm text-gray-900">{{ $comment->message }}</p>
                                @if($comment->is_internal)
                                    <span class="inline-flex items-center px-2 py-1 mt-2 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">
                                        Nota interna
                                    </span>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No hay comentarios registrados.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
