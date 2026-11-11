<?php

use App\Livewire\TicketDetail;
use App\Models\Ticket;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('un usuario no puede ver un ticket que no le pertenece', function () {
    $owner = User::factory()->create(['role' => 'usuario']);
    $other = User::factory()->create(['role' => 'usuario']);
    $ticket = Ticket::factory()->create(['reporter_id' => $owner->id]);

    actingAs($other);

    $this->get(route('tickets.show', $ticket))->assertForbidden();
});

it('un tecnico puede asignarse un ticket y cambia el estado a en progreso con historial', function () {
    $technician = User::factory()->create(['role' => 'tecnico']);
    $ticket = Ticket::factory()->create();

    actingAs($technician);

    Livewire::test(TicketDetail::class, ['ticket' => $ticket])
        ->call('assignToMe')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('tickets', [
        'id' => $ticket->id,
        'technician_id' => $technician->id,
        'status' => 'En Progreso',
    ]);

    $this->assertDatabaseHas('ticket_status_history', [
        'ticket_id' => $ticket->id,
        'old_status' => 'Abierto',
        'new_status' => 'En Progreso',
        'changed_by' => $technician->id,
    ]);

    $this->assertNotNull(Ticket::find($ticket->id)->first_response_at);
});

it('un tecnico puede cambiar el estado a resuelto y actualiza resolved_at', function () {
    $technician = User::factory()->create(['role' => 'tecnico']);
    $ticket = Ticket::factory()->create([
        'status' => 'En Progreso',
    ]);

    actingAs($technician);

    Livewire::test(TicketDetail::class, ['ticket' => $ticket])
        ->call('changeStatus', 'Resuelto')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('tickets', [
        'id' => $ticket->id,
        'status' => 'Resuelto',
    ]);

    $this->assertDatabaseHas('ticket_status_history', [
        'ticket_id' => $ticket->id,
        'old_status' => 'En Progreso',
        'new_status' => 'Resuelto',
        'changed_by' => $technician->id,
    ]);

    $this->assertNotNull(Ticket::find($ticket->id)->resolved_at);
});

it('un usuario puede agregar un comentario publico pero no uno interno', function () {
    $user = User::factory()->create(['role' => 'usuario']);
    $ticket = Ticket::factory()->create(['reporter_id' => $user->id]);

    actingAs($user);

    Livewire::test(TicketDetail::class, ['ticket' => $ticket])
        ->set('message', 'Este es un comentario valido')
        ->call('addComment', 'Este es un comentario valido', false)
        ->assertHasNoErrors();

    $this->assertDatabaseHas('ticket_comments', [
        'ticket_id' => $ticket->id,
        'user_id' => $user->id,
        'message' => 'Este es un comentario valido',
        'is_internal' => false,
    ]);

    Livewire::test(TicketDetail::class, ['ticket' => $ticket])
        ->call('addComment', 'Este es un comentario interno', true)
        ->assertForbidden();
});
