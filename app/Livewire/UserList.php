<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class UserList extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    public function mount(): void
    {
        $user = auth()->user();

        abort_unless($user && $user->can('users.manage'), 403);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    #[On('user-saved')]
    public function refreshUsers(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%');
                });
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.user-list', [
            'users' => $users,
        ]);
    }
}
