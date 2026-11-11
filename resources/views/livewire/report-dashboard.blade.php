<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Reportes y Estadísticas</h1>
            <p class="mt-1 text-sm text-gray-500">
                Indicadores de gestión de tickets para la dirección.
            </p>
        </div>
        <p class="text-xs text-gray-400">
            Actualizado: {{ now()->format('d/m/Y H:i') }}
        </p>
    </div>

    <!-- KPIs -->
    <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-3">
        <div class="rounded-lg bg-white p-6 shadow">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Tickets este mes</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        {{ number_format($totalTicketsThisMonth) }}
                    </p>
                </div>
                <div class="rounded-full bg-indigo-100 p-3 text-indigo-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h6m0 0l-3-3m3 3l-3 3M7 7h.01M7 11h.01M7 15h.01" />
                    </svg>
                </div>
            </div>
            <p class="mt-4 text-xs text-gray-500">Periodo: {{ now()->format('m/Y') }}</p>
        </div>

        <div class="rounded-lg bg-white p-6 shadow">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Tiempo promedio de resolución</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        {{ number_format($avgResolutionTime, 1) }}
                        <span class="text-base font-medium text-gray-500">h</span>
                    </p>
                </div>
                <div class="rounded-full bg-amber-100 p-3 text-amber-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="mt-4 text-xs text-gray-500">Calculado sobre tickets con fecha de resolución.</p>
        </div>

        <div class="rounded-lg bg-white p-6 shadow">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Tickets resueltos</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        {{ number_format($totalResolvedTickets) }}
                    </p>
                </div>
                <div class="rounded-full bg-emerald-100 p-3 text-emerald-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="mt-4 text-xs text-gray-500">Tickets que actualmente están en estado Resuelto.</p>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="rounded-lg bg-white p-6 shadow">
            <h2 class="text-base font-semibold text-gray-800">Tickets por Estado</h2>
            <p class="mt-1 text-sm text-gray-500">Distribución actual de la bandeja de tickets.</p>

            <div class="relative mt-6 h-80" wire:ignore>
                <canvas id="ticketsByStatusChart"></canvas>
            </div>
        </div>

        <div class="rounded-lg bg-white p-6 shadow">
            <h2 class="text-base font-semibold text-gray-800">Top 5 Categorías</h2>
            <p class="mt-1 text-sm text-gray-500">Categorías con mayor cantidad de tickets.</p>

            <div class="relative mt-6 h-80" wire:ignore>
                <canvas id="ticketsByCategoryChart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const statusLabels = @json(array_keys($ticketsByStatus));
            const statusValues = @json(array_values($ticketsByStatus));
            const categoryLabels = @json(array_column($ticketsByCategory, 'name'));
            const categoryValues = @json(array_column($ticketsByCategory, 'total'));

            const statusColors = statusLabels.map((status) => ({
                'Abierto': '#3b82f6',
                'En Progreso': '#f59e0b',
                'En Espera': '#a855f7',
                'Resuelto': '#10b981',
                'Cerrado': '#6b7280',
            }[status] ?? '#6366f1'));

            const statusCanvas = document.getElementById('ticketsByStatusChart');
            const categoryCanvas = document.getElementById('ticketsByCategoryChart');

            if (!statusCanvas || !categoryCanvas || typeof Chart === 'undefined') {
                return;
            }

            Chart.getChart(statusCanvas)?.destroy();
            Chart.getChart(categoryCanvas)?.destroy();

            new Chart(statusCanvas, {
                type: 'doughnut',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        data: statusValues,
                        backgroundColor: statusColors,
                        borderWidth: 0,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '62%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 16,
                            },
                        },
                    },
                },
            });

            new Chart(categoryCanvas, {
                type: 'bar',
                data: {
                    labels: categoryLabels,
                    datasets: [{
                        label: 'Tickets',
                        data: categoryValues,
                        backgroundColor: '#4f46e5',
                        borderRadius: 6,
                        maxBarThickness: 48,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false,
                        },
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                            },
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                            },
                            grid: {
                                color: '#e5e7eb',
                            },
                        },
                    },
                },
            });
        });
    </script>
</div>
