<?php

use App\Livewire\CreateTicket;
use App\Models\User;
use App\Models\Location;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\Site;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('puede cargar el componente de crear ticket', function () {
    $user = User::factory()->create();
    actingAs($user);

    Livewire::test(CreateTicket::class)
        ->assertStatus(200);
});

it('valida que los campos requeridos esten presentes', function () {
    $user = User::factory()->create();
    actingAs($user);

    Livewire::test(CreateTicket::class)
        ->set('priority', '') // Forzamos a que esté vacío para que falle
        ->call('save')
        ->assertHasErrors(['location_id', 'category_id', 'description', 'priority']);
});

it('puede crear un ticket exitosamente con el numero correcto', function () {
    $user = User::factory()->create();

    // Creamos los datos maestros necesarios para el test
    $site = Site::create(['name' => 'Sede Test']);
    $location = Location::create(['site_id' => $site->id, 'code' => 'LABT', 'name' => 'Lab Test']);
    $category = Category::create(['name' => 'Hardware Test']);

    actingAs($user);

    Livewire::test(CreateTicket::class)
        ->set('location_id', $location->id)
        ->set('category_id', $category->id)
        ->set('description', 'La computadora no enciende, tiene un olor extraño.')
        ->set('priority', 'Alta')
        ->call('save')
        ->assertHasNoErrors();

    // Verificar que se guardó en la BD
    $this->assertDatabaseHas('tickets', [
        'reporter_id' => $user->id,
        'location_id' => $location->id,
        'description' => 'La computadora no enciende, tiene un olor extraño.',
        'status' => 'Abierto',
    ]);

    // Verificar el formato del ticket_number (TKT-2026-0001)
    $ticket = Ticket::first();
    expect($ticket->ticket_number)->toMatch('/^TKT-\d{4}-\d{4}$/');
});
