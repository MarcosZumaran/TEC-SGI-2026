<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class UserForm extends Component
{
    public ?int $userId = null;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $role = 'usuario';

    public const ROLES = [
        'admin' => 'Administrador',
        'tecnico' => 'Técnico',
        'usuario' => 'Usuario',
        'directivo' => 'Directivo',
    ];

    public function mount(?int $userId = null): void
    {
        $this->authorizeManager();

        if ($userId === null) {
            return;
        }

        $user = User::findOrFail($userId);

        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->userId),
            ],
            'password' => $this->userId
                ? ['nullable', 'string', 'min:8']
                : ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in(array_keys(self::ROLES))],
        ];
    }

    public function save(): void
    {
        $this->authorizeManager();

        $this->name = trim($this->name);
        $this->email = trim($this->email);

        $validated = $this->validate();

        $isEditing = $this->userId !== null;

        $user = DB::transaction(function () use ($validated, $isEditing) {
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
            ];

            if (filled($validated['password'] ?? null)) {
                $userData['password'] = Hash::make($validated['password']);
            }

            if ($isEditing) {
                $user = User::findOrFail($this->userId);
                $user->update($userData);

                return $user;
            }

            return User::create($userData);
        });

        if (Role::where('name', $user->role)->exists()) {
            $user->syncRoles([$user->role]);
        }

        $this->password = '';

        if (! $isEditing) {
            $this->resetForm();
        }

        session()->flash(
            'message',
            $isEditing ? 'Usuario actualizado exitosamente.' : 'Usuario creado exitosamente.'
        );

        $this->dispatch('user-saved');
    }

    protected function authorizeManager(): void
    {
        $user = auth()->user();

        abort_unless($user && $user->can('users.manage'), 403);
    }

    protected function resetForm(): void
    {
        $this->reset([
            'name',
            'email',
            'password',
        ]);

        $this->role = 'usuario';
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.user-form', [
            'roles' => self::ROLES,
        ]);
    }
}
