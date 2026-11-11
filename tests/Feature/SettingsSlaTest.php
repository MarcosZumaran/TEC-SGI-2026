<?php

use App\Livewire\SettingsManager;
use App\Livewire\SettingsSla;
use App\Models\BusinessHour;
use App\Models\SlaPolicy;
use App\Models\Ticket;
use App\Models\User;
use App\Services\SlaScheduleSynchronizer;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('un administrador puede cambiar entre las pestañas de configuración', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    actingAs($admin);

    Livewire::test(SettingsManager::class)
        ->assertSet('activeTab', 'general')
        ->call('setTab', 'sla')
        ->assertSet('activeTab', 'sla')
        ->assertSee('SLA y Prioridades');
});

it('un administrador puede guardar las políticas SLA por prioridad', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    actingAs($admin);

    Livewire::test(SettingsSla::class)
        ->set('policies.0.response_hours', 2)
        ->set('policies.0.resolution_hours', 12)
        ->set('policies.0.is_active', true)
        ->call('savePolicies')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('sla_policies', [
        'priority' => 'Baja',
        'response_hours' => 2,
        'resolution_hours' => 12,
        'is_active' => true,
    ]);
});

it('un administrador puede guardar el horario de servicio y sincronizarlo', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    actingAs($admin);

    Livewire::test(SettingsSla::class)
        ->set('businessHours.0.start_time', '09:00')
        ->set('businessHours.0.end_time', '18:00')
        ->set('businessHours.0.is_active', true)
        ->call('saveBusinessHours')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('business_hours', [
        'day_of_week' => 1,
        'start_time' => '09:00:00',
        'end_time' => '18:00:00',
        'is_active' => true,
    ]);

    $this->assertDatabaseHas('service_desk_business_hours_schedules', [
        'name' => SlaScheduleSynchronizer::SCHEDULE_NAME,
        'is_default' => true,
        'is_active' => true,
    ]);

    $this->assertDatabaseHas('service_desk_business_hours_time_slots', [
        'day_of_week' => 1,
        'start_time' => '09:00:00',
        'end_time' => '18:00:00',
    ]);
});

it('calcula el vencimiento SLA al crear un ticket', function () {
    SlaPolicy::ensureDefaults();
    BusinessHour::ensureDefaults();
    app(SlaScheduleSynchronizer::class)->synchronize();

    $ticket = Ticket::factory()->create([
        'priority' => 'Media',
    ]);

    $ticket->refresh();

    expect($ticket->due_at)->not->toBeNull()
        ->and($ticket->due_at->greaterThan($ticket->created_at))->toBeTrue();
});
