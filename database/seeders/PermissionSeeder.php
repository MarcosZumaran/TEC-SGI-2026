<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * @var array<int, string>
     */
    public const PERMISSIONS = [
        'tickets.view',
        'tickets.create',
        'tickets.assign',
        'tickets.close',
        'assets.view',
        'assets.manage',
        'users.manage',
        'settings.manage',
        'reports.view',
    ];

    /**
     * Permisos iniciales por rol.
     *
     * @var array<string, array<int, string>>
     */
    public const ROLE_PERMISSIONS = [
        'admin' => self::PERMISSIONS,
        'tecnico' => [
            'tickets.view',
            'tickets.create',
            'tickets.assign',
            'tickets.close',
            'assets.view',
            'assets.manage',
        ],
        'usuario' => [
            'tickets.view',
            'tickets.create',
        ],
        'directivo' => [
            'tickets.view',
            'reports.view',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::PERMISSIONS as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        foreach (self::ROLE_PERMISSIONS as $roleName => $permissions) {
            $role = Role::findOrCreate($roleName, 'web');
            $role->syncPermissions($permissions);
        }

        User::query()->each(function (User $user): void {
            if (array_key_exists($user->role, self::ROLE_PERMISSIONS)) {
                $user->syncRoles([$user->role]);
            }
        });
    }
}
