<?php

use App\Livewire\NotificationBell;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketAssignedNotification;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('un usuario puede ver sus notificaciones', function () {
    $user = User::factory()->create(['role' => 'usuario']);
    $ticket = Ticket::factory()->create();

    $user->notify(new TicketAssignedNotification($ticket));

    actingAs($user);

    $this->get(route('notifications.index'))
        ->assertOk()
        ->assertSee('Centro de Notificaciones')
        ->assertSee('Ticket asignado');

    Livewire::test(NotificationBell::class)
        ->assertSee('Ticket asignado');
});

it('al marcar una notificación como leída se actualiza read_at', function () {
    $user = User::factory()->create(['role' => 'usuario']);
    $ticket = Ticket::factory()->create();

    $user->notify(new TicketAssignedNotification($ticket));

    $notification = $user->notifications()->firstOrFail();

    actingAs($user);

    Livewire::test(NotificationBell::class)
        ->call('markAsRead', $notification->id);

    expect($notification->fresh()->read_at)->not->toBeNull();
});

it('un usuario no puede ver ni marcar notificaciones de otro usuario', function () {
    $owner = User::factory()->create(['role' => 'usuario']);
    $other = User::factory()->create(['role' => 'usuario']);
    $ticket = Ticket::factory()->create();

    $owner->notify(new TicketAssignedNotification($ticket));

    $notification = $owner->notifications()->firstOrFail();

    actingAs($other);

    $this->get(route('notifications.index'))
        ->assertOk()
        ->assertDontSee('Ticket asignado');

    Livewire::test(NotificationBell::class)
        ->call('markAsRead', $notification->id)
        ->assertNotFound();
});
