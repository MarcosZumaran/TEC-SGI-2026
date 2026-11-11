<?php

use App\Livewire\TicketList;
use App\Models\User;
use App\Models\Ticket;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('un usuario normal solo ve sus propios tickets', function () {
    $user1 = User::factory()->create(['role' => 'usuario']);
    $user2 = User::factory()->create(['role' => 'usuario']);

    $ticket1 = Ticket::factory()->create(['reporter_id' => $user1->id, 'description' => 'Falla de User1']);
    $ticket2 = Ticket::factory()->create(['reporter_id' => $user2->id, 'description' => 'Falla de User2']);

    actingAs($user1);

    Livewire::test(TicketList::class)
        ->assertSee('Falla de User1')
        ->assertDontSee('Falla de User2');
});

it('un administrador ve todos los tickets', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['role' => 'usuario']);

    $ticket = Ticket::factory()->create(['reporter_id' => $user->id, 'description' => 'Falla general']);

    actingAs($admin);

    Livewire::test(TicketList::class)
        ->assertSee('Falla general');
});
