<?php

use App\Livewire\AssetList;
use App\Models\Asset;
use App\Models\Location;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('un administrador puede ver la lista de equipos', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $location = Location::factory()->create();

    Asset::create([
        'asset_code' => 'PC-ADMIN-001',
        'type' => 'PC',
        'brand' => 'Dell',
        'model' => 'OptiPlex 7010',
        'serial_number' => 'SN-ADMIN-001',
        'location_id' => $location->id,
        'status' => 'Operativo',
    ]);

    actingAs($admin);

    $this->get(route('assets.index'))
        ->assertOk()
        ->assertSee('Inventario de Equipos')
        ->assertSee('PC-ADMIN-001');
});

it('un usuario no puede ver la lista de equipos', function () {
    $user = User::factory()->create(['role' => 'usuario']);

    actingAs($user);

    $this->get(route('assets.index'))->assertForbidden();
});

it('el buscador filtra correctamente por código de activo', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $location = Location::factory()->create();

    Asset::create([
        'asset_code' => 'PC-TEST-001',
        'type' => 'PC',
        'brand' => 'HP',
        'model' => 'ProDesk 400 G7',
        'serial_number' => 'SN-TEST-001',
        'location_id' => $location->id,
        'status' => 'Operativo',
    ]);

    Asset::create([
        'asset_code' => 'PC-OTHER-002',
        'type' => 'PC',
        'brand' => 'Lenovo',
        'model' => 'ThinkCentre M70q',
        'serial_number' => 'SN-OTHER-002',
        'location_id' => $location->id,
        'status' => 'Con Falla',
    ]);

    actingAs($admin);

    Livewire::test(AssetList::class)
        ->set('search', 'PC-TEST')
        ->assertSee('PC-TEST-001')
        ->assertDontSee('PC-OTHER-002');
});
