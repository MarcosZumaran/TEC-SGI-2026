<?php

use App\Livewire\ReportDashboard;
use App\Models\Ticket;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('un directivo puede ver la página de reportes', function () {
    $directivo = User::factory()->create(['role' => 'directivo']);

    actingAs($directivo);

    $this->get(route('reports.index'))
        ->assertOk()
        ->assertSee('Reportes y Estadísticas');
});

it('un técnico no puede ver la página de reportes', function () {
    $technician = User::factory()->create(['role' => 'tecnico']);

    actingAs($technician);

    $this->get(route('reports.index'))->assertForbidden();
});

it('un admin puede ver la página y el componente calcula el total de tickets', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Ticket::factory()->create([
        'status' => 'Resuelto',
        'resolved_at' => now()->addHour(),
        'created_at' => now(),
    ]);
    Ticket::factory()->count(2)->create(['created_at' => now()]);
    Ticket::factory()->create(['created_at' => now()->startOfMonth()->subMinute()]);

    actingAs($admin);

    $this->get(route('reports.index'))->assertOk();

    Livewire::test(ReportDashboard::class)
        ->assertSet('totalTicketsThisMonth', 3)
        ->assertSet('totalResolvedTickets', 1)
        ->assertSet('avgResolutionTime', 1.0)
        ->assertSet('ticketsByStatus.Resuelto', 1);
});
