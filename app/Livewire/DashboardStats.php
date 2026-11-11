<?php

namespace App\Livewire;

use App\Models\Asset;
use App\Models\Ticket;
use Livewire\Component;

class DashboardStats extends Component
{
    public $openTickets = 0;

    public $inProgressTickets = 0;

    public $operationalAssets = 0;

    public $failedAssets = 0;

    public function mount(): void
    {
        $openTicketQuery = Ticket::query();

        if (auth()->check() && auth()->user()->role === 'usuario') {
            $openTicketQuery->where('reporter_id', auth()->id());
        }

        $this->openTickets = (int) $openTicketQuery
            ->where('status', 'Abierto')
            ->count();

        $this->inProgressTickets = (int) Ticket::query()
            ->where('status', 'En Progreso')
            ->count();

        $this->operationalAssets = (int) Asset::query()
            ->where('status', 'Operativo')
            ->count();

        $this->failedAssets = (int) Asset::query()
            ->where('status', 'Con Falla')
            ->count();
    }

    public function render()
    {
        return view('livewire.dashboard-stats');
    }
}
