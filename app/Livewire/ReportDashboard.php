<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Ticket;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ReportDashboard extends Component
{
    /**
     * Estados considerados en la distribución del dashboard.
     *
     * @var array<int, string>
     */
    private const STATUSES = [
        'Abierto',
        'En Progreso',
        'En Espera',
        'Resuelto',
        'Cerrado',
    ];

    /**
     * Tickets agrupados por estado.
     *
     * @var array<string, int>
     */
    public array $ticketsByStatus = [];

    /**
     * Top 5 de categorías con más tickets.
     *
     * @var array<int, array{name: string, total: int}>
     */
    public array $ticketsByCategory = [];

    /**
     * Tiempo promedio de resolución en horas.
     */
    public float $avgResolutionTime = 0.0;

    /**
     * Tickets creados durante el mes actual.
     */
    public int $totalTicketsThisMonth = 0;

    /**
     * Tickets que actualmente tienen estado Resuelto.
     */
    public int $totalResolvedTickets = 0;

    public function mount(): void
    {
        $user = auth()->user();

        abort_unless($user && $user->can('reports.view'), 403);

        $this->ticketsByStatus = $this->getTicketsByStatus();
        $this->ticketsByCategory = $this->getTicketsByCategory();
        $this->avgResolutionTime = $this->getAverageResolutionTime();
        $this->totalTicketsThisMonth = $this->getTotalTicketsThisMonth();
        $this->totalResolvedTickets = Ticket::query()
            ->where('status', 'Resuelto')
            ->count();
    }

    /**
     * @return array<string, int>
     */
    private function getTicketsByStatus(): array
    {
        $counts = Ticket::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $ticketsByStatus = [];

        foreach (self::STATUSES as $status) {
            $ticketsByStatus[$status] = (int) ($counts[$status] ?? 0);
        }

        return $ticketsByStatus;
    }

    /**
     * @return array<int, array{name: string, total: int}>
     */
    private function getTicketsByCategory(): array
    {
        return Category::query()
            ->withCount('tickets')
            ->has('tickets')
            ->orderByDesc('tickets_count')
            ->limit(5)
            ->get()
            ->map(fn (Category $category): array => [
                'name' => $category->name,
                'total' => (int) $category->tickets_count,
            ])
            ->all();
    }

    private function getAverageResolutionTime(): float
    {
        $resolvedTickets = Ticket::query()
            ->whereNotNull('resolved_at')
            ->get(['created_at', 'resolved_at']);

        if ($resolvedTickets->isEmpty()) {
            return 0.0;
        }

        $totalSeconds = $resolvedTickets->sum(function (Ticket $ticket): float {
            return (float) $ticket->created_at->diffInSeconds($ticket->resolved_at, true);
        });

        $averageHours = ($totalSeconds / 3600) / $resolvedTickets->count();

        return round($averageHours, 1);
    }

    private function getTotalTicketsThisMonth(): int
    {
        return Ticket::query()
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();
    }

    public function render()
    {
        return view('livewire.report-dashboard');
    }
}
