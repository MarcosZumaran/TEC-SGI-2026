<?php

use App\Livewire\UserForm;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('un administrador puede ver la lista de usuarios', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    actingAs($admin);

    $this->get(route('users.index'))
        ->assertOk()
        ->assertSee('Gestión de Usuarios');
});

it('un técnico no puede ver la lista de usuarios', function () {
    $technician = User::factory()->create(['role' => 'tecnico']);

    actingAs($technician);

    $this->get(route('users.index'))->assertForbidden();
});

it('un administrador puede crear un usuario técnico con contraseña hasheada', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    actingAs($admin);

    Livewire::test(UserForm::class)
        ->set('name', 'Nuevo Técnico')
        ->set('email', 'nuevo.tecnico@example.com')
        ->set('password', 'password123')
        ->set('role', 'tecnico')
        ->call('save')
        ->assertHasNoErrors()
        ->assertDispatched('user-saved');

    $user = User::where('email', 'nuevo.tecnico@example.com')->first();

    expect($user)->not->toBeNull()
        ->and($user->role)->toBe('tecnico')
        ->and($user->password)->not->toBe('password123')
        ->and(Hash::check('password123', $user->getRawOriginal('password')))->toBeTrue();
});

it('un administrador puede editar el rol de un usuario existente', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['role' => 'usuario']);

    actingAs($admin);

    Livewire::test(UserForm::class, ['userId' => $user->id])
        ->set('role', 'directivo')
        ->call('save')
        ->assertHasNoErrors()
        ->assertDispatched('user-saved');

    expect($user->fresh()->role)->toBe('directivo')
        ->and(Hash::check('password', $user->fresh()->getRawOriginal('password')))->toBeTrue();
});
