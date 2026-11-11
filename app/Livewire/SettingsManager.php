<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class SettingsManager extends Component
{
    public string $activeTab = 'general';

    public const TABS = [
        'general' => 'General',
        'sla' => 'SLA y Prioridades',
        'categories' => 'Categorías',
        'notifications' => 'Notificaciones',
        'security' => 'Seguridad',
    ];

    public function mount(): void
    {
        $this->authorizeAdmin();
    }

    public function setTab(string $tabName): void
    {
        $this->authorizeAdmin();

        abort_unless(array_key_exists($tabName, self::TABS), 404);

        $this->activeTab = $tabName;
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
        return view('livewire.settings-manager', [
            'tabs' => self::TABS,
        ]);
    }
}
