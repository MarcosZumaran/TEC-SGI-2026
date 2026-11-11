<?php

namespace App\Livewire;

use App\Settings\NotificationSettings;
use Livewire\Component;

class SettingsNotifications extends Component
{
    public string $activeTemplate = 'ticket_created';

    public string $subject = '';

    public string $body = '';

    public function mount(): void
    {
        $this->authorizeSettings();
        $this->loadTemplate();
    }

    public function selectTemplate(string $template): void
    {
        $this->authorizeSettings();

        abort_unless(array_key_exists($template, NotificationSettings::TEMPLATES), 404);

        $this->activeTemplate = $template;
        $this->loadTemplate();
    }

    public function save(): void
    {
        $this->authorizeSettings();

        $this->subject = trim($this->subject);

        $validated = $this->validate([
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:5000'],
        ]);

        NotificationSettings::save(
            $this->activeTemplate,
            $validated['subject'],
            $validated['body']
        );

        session()->flash('notification_message', 'Plantilla actualizada correctamente.');
    }

    private function loadTemplate(): void
    {
        $template = NotificationSettings::get($this->activeTemplate);

        $this->subject = $template['subject'];
        $this->body = $template['body'];
    }

    private function authorizeSettings(): void
    {
        abort_unless(
            auth()->check() && auth()->user()->can('settings.manage'),
            403
        );
    }

    public function render()
    {
        return view('livewire.settings-notifications', [
            'templates' => NotificationSettings::TEMPLATES,
        ]);
    }
}
