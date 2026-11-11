<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ticket;

#[Layout('layouts.app')]
class TicketList extends Component
{
    use WithPagination;

    #[Url(as: 'q')] // Mantiene la búsqueda en la URL
    public $search = '';

    #[Url]
    public $status = '';

    #[Url]
    public $priority = '';

    public function updatingSearch()
    {
        $this->resetPage(); // Vuelve a la página 1 al buscar
    }

    public function render()
    {
        $user = auth()->user();

        $query = Ticket::with(['reporter', 'technician', 'category', 'location'])
            ->orderBy('created_at', 'desc');

        // Lógica de visibilidad por roles
        if ($user->role === 'usuario') {
            $query->where('reporter_id', $user->id);
        } elseif ($user->role === 'tecnico') {
            $query->where(function($q) use ($user) {
                $q->where('technician_id', $user->id)
                  ->orWhereNull('technician_id'); // Ve los suyos y los que no tienen técnico
            });
        }
        // Admin y directivo ven todo (no filtramos)

        // Aplicar filtros de búsqueda
        if ($this->search) {
            $query->where(function($q) {
                $q->where('ticket_number', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->priority) {
            $query->where('priority', $this->priority);
        }

        return view('livewire.ticket-list', [
            'tickets' => $query->paginate(10)
        ]);
    }
}
