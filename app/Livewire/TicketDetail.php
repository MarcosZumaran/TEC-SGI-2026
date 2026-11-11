<?php

namespace App\Livewire;

use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\TicketStatusHistory;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class TicketDetail extends Component
{
    public $ticket;

    public $message = '';

    public $isInternal = false;

    public $newStatus = 'Abierto';

    public function mount(Ticket $ticket): void
    {
        $this->ticket = $ticket->load([
            'reporter',
            'technician',
            'asset',
            'location',
            'category',
            'comments.user',
            'statusHistory',
        ]);

        $user = auth()->user();

        if ($user->role === 'usuario' && $ticket->reporter_id !== $user->id) {
            abort(403);
        }

        $this->newStatus = $ticket->status;
    }

    public function assignToMe(): void
    {
        $user = auth()->user();

        abort_unless($user->can('tickets.assign'), 403);

        $oldStatus = $this->ticket->status;

        $this->ticket->update([
            'technician_id' => $user->id,
            'status' => 'En Progreso',
            'first_response_at' => $this->ticket->first_response_at ?? now(),
        ]);

        TicketStatusHistory::create([
            'ticket_id' => $this->ticket->id,
            'old_status' => $oldStatus,
            'new_status' => 'En Progreso',
            'changed_by' => $user->id,
        ]);

        $this->refreshTicket();
    }

    public function changeStatus($newStatus): void
    {
        $oldStatus = $this->ticket->status;

        $this->ticket->update(['status' => $newStatus]);

        if ($newStatus === 'Resuelto') {
            $this->ticket->update(['resolved_at' => now()]);
        }

        TicketStatusHistory::create([
            'ticket_id' => $this->ticket->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by' => auth()->id(),
        ]);

        $this->refreshTicket();
    }

    public function addComment($message, $isInternal): void
    {
        $user = auth()->user();

        if ($isInternal && ! in_array($user->role, ['tecnico', 'admin'], true)) {
            abort(403);
        }

        TicketComment::create([
            'ticket_id' => $this->ticket->id,
            'user_id' => $user->id,
            'message' => $message,
            'is_internal' => $isInternal,
        ]);

        $this->message = '';

        $this->isInternal = false;

        $this->refreshTicket();
    }

    protected function refreshTicket(): void
    {
        $this->ticket = $this->ticket->fresh([
            'reporter',
            'technician',
            'asset',
            'location',
            'category',
            'comments.user',
            'statusHistory',
        ]) ?? $this->ticket;
    }

    public function render()
    {
        return view('livewire.ticket-detail', [
            'ticket' => $this->ticket,
        ]);
    }
}
