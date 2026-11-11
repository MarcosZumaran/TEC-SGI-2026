<?php

namespace App\Livewire;

use Illuminate\Validation\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class SettingsSecurity extends Component
{
    /**
     * @var array<int, string>
     */
    public const ROLE_NAMES = [
        'admin',
        'tecnico',
        'usuario',
        'directivo',
    ];

    /**
     * @var array<int, array{name: string}>
     */
    public array $roles = [];

    /**
     * @var array<int, string>
     */
    public array $permissions = [];

    /**
     * @var array<string, array<int, string>>
     */
    public array $selectedPermissions = [];

    public function mount(): void
    {
        $this->authorizeSettings();
        $this->loadData();
    }

    public function save(): void
    {
        $this->authorizeSettings();

        $this->validate([
            'selectedPermissions' => ['array'],
            'selectedPermissions.*' => ['array'],
            'selectedPermissions.*.*' => ['string', Rule::exists('permissions', 'name')],
        ]);

        foreach (self::ROLE_NAMES as $roleName) {
            $permissions = array_values(array_intersect(
                $this->selectedPermissions[$roleName] ?? [],
                $this->permissions
            ));

            Role::findByName($roleName, 'web')->syncPermissions($permissions);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        session()->flash('security_message', 'Permisos actualizados correctamente.');

        $this->loadData();
    }

    private function loadData(): void
    {
        $roles = Role::query()
            ->whereIn('name', self::ROLE_NAMES)
            ->with('permissions')
            ->get()
            ->keyBy('name');

        $this->roles = [];
        $this->selectedPermissions = [];

        foreach (self::ROLE_NAMES as $roleName) {
            $role = $roles->get($roleName) ?? Role::findOrCreate($roleName, 'web');
            $role->load('permissions');

            $this->roles[] = ['name' => $role->name];
            $this->selectedPermissions[$role->name] = $role->permissions
                ->pluck('name')
                ->values()
                ->all();
        }

        $this->permissions = Permission::query()
            ->orderBy('name')
            ->pluck('name')
            ->values()
            ->all();
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
        return view('livewire.settings-security');
    }
}
