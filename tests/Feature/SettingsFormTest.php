<?php

use App\Livewire\SettingsForm;
use App\Models\User;
use App\Settings\GeneralSettings;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    Cache::flush();
});

afterEach(function () {
    Cache::flush();
});

it('un administrador puede ver la configuracion del sistema', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    actingAs($admin);

    $this->get(route('settings.index'))
        ->assertOk()
        ->assertSee('Configuración');
});

it('un tecnico no puede acceder a la configuracion del sistema', function () {
    $technician = User::factory()->create(['role' => 'tecnico']);

    actingAs($technician);

    $this->get(route('settings.index'))->assertForbidden();
});

it('un administrador puede guardar la configuracion general', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    actingAs($admin);

    Livewire::test(SettingsForm::class)
        ->assertSet('site_name', 'TEC-SGI 2026')
        ->assertSet('items_per_page', 10)
        ->assertSet('maintenance_mode', false)
        ->set('site_name', '  TEC-SGI Instituto  ')
        ->set('items_per_page', '25')
        ->set('maintenance_mode', true)
        ->call('save')
        ->assertHasNoErrors()
        ->assertSet('site_name', 'TEC-SGI Instituto');

    $this->assertDatabaseHas('beartropy_settings', [
        'group' => 'general',
        'key' => 'site_name',
        'value' => 'TEC-SGI Instituto',
        'type' => 'text',
    ]);

    $this->assertDatabaseHas('beartropy_settings', [
        'group' => 'general',
        'key' => 'items_per_page',
        'value' => '25',
        'type' => 'number',
    ]);

    $this->assertDatabaseHas('beartropy_settings', [
        'group' => 'general',
        'key' => 'maintenance_mode',
        'value' => '1',
        'type' => 'boolean',
    ]);

    expect(GeneralSettings::all())->toMatchArray([
        'site_name' => 'TEC-SGI Instituto',
        'items_per_page' => 25,
        'maintenance_mode' => true,
    ]);
});
