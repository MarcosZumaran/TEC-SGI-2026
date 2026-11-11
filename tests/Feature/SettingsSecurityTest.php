<?php

use App\Livewire\SettingsSecurity;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;

it('permite al administrador actualizar los permisos de un rol', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    actingAs($admin);

    Livewire::test(SettingsSecurity::class)
        ->set('selectedPermissions.usuario', [
            'tickets.view',
            'tickets.create',
            'assets.view',
        ])
        ->call('save')
        ->assertHasNoErrors();

    $role = Role::findByName('usuario', 'web');

    expect($role->hasPermissionTo('assets.view'))->toBeTrue();
});

it('rechaza a un usuario normal en la gestión de seguridad', function () {
    $user = User::factory()->create(['role' => 'usuario']);
    actingAs($user);

    Livewire::test(SettingsSecurity::class)->assertForbidden();
});
