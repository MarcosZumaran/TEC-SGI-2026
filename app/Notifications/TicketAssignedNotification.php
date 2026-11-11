<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TicketAssignedNotification extends Notification
{
    use Queueable;

    public function __construct(public Ticket $ticket) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array{title: string, message: string, url: string, icon: string}
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Ticket asignado',
            'message' => 'Se te asignó el ticket '.$this->ticket->ticket_number.'.',
            'url' => route('tickets.show', $this->ticket),
            'icon' => 'ticket',
        ];
    }
}
