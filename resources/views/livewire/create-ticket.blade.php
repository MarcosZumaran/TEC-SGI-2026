<div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Reportar Nueva Incidencia</h2>

            @if (session()->has('message'))
                <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit="save" class="space-y-6">
                <!-- Ubicación -->
                <div>
                    <label for="location_id" class="block text-sm font-medium text-gray-700">Ubicación donde ocurre el problema</label>
                    <select wire:model="location_id" id="location_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Seleccione una ubicación...</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                        @endforeach
                    </select>
                    @error('location_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Categoría -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Categoría de la incidencia</label>
                    <select wire:model="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Seleccione una categoría...</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Activo (Opcional) -->
                <div>
                    <label for="asset_id" class="block text-sm font-medium text-gray-700">Equipo afectado (Opcional)</label>
                    <select wire:model="asset_id" id="asset_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">No aplica / No sé el equipo</option>
                        @foreach($assets as $asset)
                            <option value="{{ $asset->id }}">{{ $asset->asset_code }} - {{ $asset->brand }} {{ $asset->model }}</option>
                        @endforeach
                    </select>
                    @error('asset_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Descripción -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Descripción detallada del problema</label>
                    <textarea wire:model="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Describa el problema con el mayor detalle posible..."></textarea>
                    @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Prioridad -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700">Prioridad sugerida</label>
                    <select wire:model="priority" id="priority" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="Baja">Baja</option>
                        <option value="Media">Media</option>
                        <option value="Alta">Alta</option>
                        <option value="Critica">Crítica</option>
                    </select>
                    @error('priority') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Botón -->
                <div class="flex items-center justify-end mt-4">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <span wire:loading.remove wire:target="save">Registrar Ticket</span>
                        <span wire:loading wire:target="save">Guardando...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
