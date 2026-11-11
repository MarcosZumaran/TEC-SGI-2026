<?php

use App\Livewire\SettingsNotifications;
use App\Models\User;
use App\Settings\NotificationSettings;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('permite al administrador actualizar una plantilla de notificación', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    actingAs($admin);

    Livewire::test(SettingsNotifications::class)
        ->set('subject', 'Asunto actualizado')
        ->set('body', 'Cuerpo actualizado para {{ticket_number}}')
        ->call('save')
        ->assertHasNoErrors();

    $template = NotificationSettings::get('ticket_created');

    expect($template['subject'])->toBe('Asunto actualizado')
        ->and($template['body'])->toBe('Cuerpo actualizado para {{ticket_number}}');
});

it('rechaza a un usuario normal en la gestión de notificaciones', function () {
    $user = User::factory()->create(['role' => 'usuario']);
    actingAs($user);

    Livewire::test(SettingsNotifications::class)->assertForbidden();
});
