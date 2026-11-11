<?php

namespace App\Livewire;

use App\Settings\GeneralSettings;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class SettingsForm extends Component
{
    public string $site_name = '';

    public $items_per_page = 10;

    public bool $maintenance_mode = false;

    public function mount(): void
    {
        $this->authorizeAdmin();

        $settings = GeneralSettings::all();

        $this->site_name = $settings['site_name'];
        $this->items_per_page = $settings['items_per_page'];
        $this->maintenance_mode = $settings['maintenance_mode'];
    }

    public function save(): void
    {
        $this->authorizeAdmin();

        $this->site_name = trim($this->site_name);

        $validated = $this->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'items_per_page' => ['required', 'integer', 'min:1', 'max:100'],
            'maintenance_mode' => ['boolean'],
        ]);

        GeneralSettings::save([
            GeneralSettings::SITE_NAME => $validated['site_name'],
            GeneralSettings::ITEMS_PER_PAGE => (int) $validated['items_per_page'],
            GeneralSettings::MAINTENANCE_MODE => (bool) $validated['maintenance_mode'],
        ]);

        session()->flash('message', 'Configuración actualizada correctamente.');

        $this->dispatch('settings-saved');
    }

    private function authorizeAdmin(): void
    {
        abort_unless(
            auth()->check() && auth()->user()->can('settings.manage'),
            403
        );
    }

    public function render()
    {
        return view('livewire.settings-form');
    }
}
