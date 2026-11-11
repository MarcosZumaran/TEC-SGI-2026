<?php

use App\Livewire\AssetForm;
use App\Models\Asset;
use App\Models\Location;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('un tecnico puede crear un activo PC con sus especificaciones tecnicas', function () {
    $technician = User::factory()->create(['role' => 'tecnico']);
    $location = Location::factory()->create();

    actingAs($technician);

    Livewire::test(AssetForm::class)
        ->set('asset_code', 'PC-TEST-100')
        ->set('type', 'PC')
        ->set('brand', 'Dell')
        ->set('model', 'OptiPlex 7010')
        ->set('serial_number', 'SN-TEST-100')
        ->set('location_id', $location->id)
        ->set('responsible_user_id', $technician->id)
        ->set('status', 'Operativo')
        ->set('acquisition_date', '2025-01-15')
        ->set('observations', 'Equipo de pruebas')
        ->set('processor', 'Intel Core i5-12400')
        ->set('ram', '16 GB')
        ->set('disk', 'SSD 512 GB')
        ->set('os', 'Windows 11 Pro')
        ->set('ip_address', '192.168.1.10')
        ->set('mac_address', 'AA:BB:CC:DD:EE:FF')
        ->call('save')
        ->assertHasNoErrors()
        ->assertDispatched('asset-saved')
        ->assertSet('asset_code', '');

    $asset = Asset::where('asset_code', 'PC-TEST-100')->firstOrFail();

    $this->assertDatabaseHas('assets', [
        'id' => $asset->id,
        'asset_code' => 'PC-TEST-100',
        'type' => 'PC',
        'brand' => 'Dell',
        'model' => 'OptiPlex 7010',
        'serial_number' => 'SN-TEST-100',
        'location_id' => $location->id,
        'responsible_user_id' => $technician->id,
        'status' => 'Operativo',
        'observations' => 'Equipo de pruebas',
    ]);

    $this->assertDatabaseHas('asset_specs', [
        'asset_id' => $asset->id,
        'processor' => 'Intel Core i5-12400',
        'ram' => '16 GB',
        'disk' => 'SSD 512 GB',
        'os' => 'Windows 11 Pro',
        'ip_address' => '192.168.1.10',
        'mac_address' => 'AA:BB:CC:DD:EE:FF',
    ]);
});

it('el codigo de activo debe ser unico', function () {
    $technician = User::factory()->create(['role' => 'tecnico']);
    $location = Location::factory()->create();

    Asset::create([
        'asset_code' => 'PC-DUPLICATE-001',
        'type' => 'PC',
        'location_id' => $location->id,
        'status' => 'Operativo',
    ]);

    actingAs($technician);

    Livewire::test(AssetForm::class)
        ->set('asset_code', 'PC-DUPLICATE-001')
        ->set('type', 'PC')
        ->set('location_id', $location->id)
        ->set('status', 'Operativo')
        ->call('save')
        ->assertHasErrors('asset_code');

    $this->assertDatabaseCount('assets', 1);
});

it('un tecnico puede editar un activo existente y sus especificaciones', function () {
    $technician = User::factory()->create(['role' => 'tecnico']);
    $location = Location::factory()->create();

    $asset = Asset::create([
        'asset_code' => 'PC-EDIT-001',
        'type' => 'PC',
        'brand' => 'HP',
        'model' => 'ProDesk 400 G7',
        'serial_number' => 'SN-EDIT-001',
        'location_id' => $location->id,
        'status' => 'Operativo',
    ]);

    $asset->specs()->create([
        'processor' => 'Intel Core i3-10100',
        'ram' => '8 GB',
        'disk' => 'SSD 256 GB',
        'os' => 'Windows 10 Pro',
        'ip_address' => '192.168.1.20',
        'mac_address' => '11:22:33:44:55:66',
    ]);

    actingAs($technician);

    Livewire::test(AssetForm::class, ['assetId' => $asset->id])
        ->assertSet('asset_code', 'PC-EDIT-001')
        ->assertSet('brand', 'HP')
        ->assertSet('ram', '8 GB')
        ->set('brand', 'Lenovo')
        ->set('status', 'Con Falla')
        ->set('ram', '16 GB')
        ->call('save')
        ->assertHasNoErrors()
        ->assertDispatched('asset-saved');

    $this->assertDatabaseHas('assets', [
        'id' => $asset->id,
        'asset_code' => 'PC-EDIT-001',
        'brand' => 'Lenovo',
        'status' => 'Con Falla',
    ]);

    $this->assertDatabaseHas('asset_specs', [
        'asset_id' => $asset->id,
        'processor' => 'Intel Core i3-10100',
        'ram' => '16 GB',
        'disk' => 'SSD 256 GB',
    ]);

    $this->assertDatabaseCount('asset_specs', 1);
});
