<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <!-- Encabezado -->
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Base de Conocimientos</h1>
            <p class="mt-1 text-sm text-gray-500">
                Consulta procedimientos, soluciones temporales y errores conocidos.
            </p>
        </div>

        @if ($canManage)
            <button
                type="button"
                x-data
                x-on:click="$dispatch('open-knowledge-form')"
                class="inline-flex flex-shrink-0 items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                + Nuevo Artículo
            </button>
        @endif
    </div>

    <!-- Filtros -->
    <div class="rounded-lg bg-white p-6 shadow">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div>
                <label for="knowledge-search" class="block text-sm font-medium text-gray-700">
                    Buscar por título
                </label>
                <input
                    id="knowledge-search"
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Escribe el título del artículo..."
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
            </div>

            <div>
                <label for="knowledge-category" class="block text-sm font-medium text-gray-700">
                    Categoría
                </label>
                <select
                    id="knowledge-category"
                    wire:model.live="category"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
                    <option value="">Todas las categorías</option>
                    @foreach ($categories as $categoryOption)
                        <option value="{{ $categoryOption->id }}">{{ $categoryOption->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="knowledge-type" class="block text-sm font-medium text-gray-700">
                    Tipo
                </label>
                <select
                    id="knowledge-type"
                    wire:model.live="type"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
                    <option value="">Todos los tipos</option>
                    @foreach ($types as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    @if (session('message'))
        <div class="mt-6 rounded-lg border border-green-200 bg-green-50 px-5 py-3 text-sm font-medium text-green-800">
            {{ session('message') }}
        </div>
    @endif

    <!-- Artículos -->
    <div class="mt-6 grid grid-cols-1 gap-5 lg:grid-cols-2">
        @forelse ($knowledge as $article)
            <article
                wire:key="knowledge-{{ $article->id }}"
                class="flex h-full flex-col rounded-lg border border-gray-200 bg-white p-6 shadow-sm transition hover:shadow-md"
            >
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <h2 class="text-lg font-bold text-gray-900">
                            {{ $article->title }}
                        </h2>

                        <div class="mt-3 flex flex-wrap items-center gap-2">
                            <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-semibold text-gray-700">
                                {{ $article->category?->name ?? 'Sin categoría' }}
                            </span>

                            <span @class([
                                'inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold',
                                'bg-blue-100 text-blue-800' => $article->type === 'faq',
                                'bg-amber-100 text-amber-800' => $article->type === 'workaround',
                                'bg-red-100 text-red-800' => $article->type === 'known_error',
                            ])>
                                {{ $types[$article->type] ?? ucfirst($article->type) }}
                            </span>

                            <span @class([
                                'inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold',
                                'bg-green-100 text-green-800' => $article->is_published,
                                'bg-yellow-100 text-yellow-800' => ! $article->is_published,
                            ])>
                                {{ $article->is_published ? 'Publicado' : 'Borrador' }}
                            </span>
                        </div>
                    </div>

                    @if ($canManage)
                        <div class="flex-shrink-0">
                            <livewire:knowledge-form
                                :knowledgeId="$article->id"
                                :key="'knowledge-form-' . $article->id"
                            />
                        </div>
                    @endif
                </div>

                <p class="mt-4 flex-1 text-sm leading-6 text-gray-600">
                    {{ Str::limit($article->content, 220) }}
                </p>

                <div class="mt-5 flex items-center justify-between border-t border-gray-100 pt-4 text-xs text-gray-500">
                    <span>Creado por {{ $article->creator?->name ?? 'Sistema' }}</span>
                    <span>{{ $article->updated_at?->format('d/m/Y') }}</span>
                </div>
            </article>
        @empty
            <div class="col-span-full rounded-lg border-2 border-dashed border-gray-300 bg-white px-6 py-12 text-center">
                <p class="text-sm font-medium text-gray-600">
                    No se encontraron artículos con los filtros seleccionados.
                </p>
            </div>
        @endforelse
    </div>

    <!-- Paginación -->
    <div class="mt-6">
        {{ $knowledge->links() }}
    </div>

    @if ($canManage)
        <livewire:knowledge-form />
    @endif
</div>
