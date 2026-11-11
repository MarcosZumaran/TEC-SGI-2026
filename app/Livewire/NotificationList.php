<?php

namespace App\Livewire;

use Illuminate\Notifications\DatabaseNotification;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class NotificationList extends Component
{
    use WithPagination;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $selectedNotification = null;

    public bool $showNotificationModal = false;

    public function markAsRead(string $id): void
    {
        $user = auth()->user();

        abort_if($user === null, 403);

        $notification = $user->notifications()
            ->whereKey($id)
            ->first();

        abort_if($notification === null, 404);

        $notification->markAsRead();

        $this->selectedNotification = $this->formatNotification($notification);
        $this->showNotificationModal = true;

        $this->dispatch('notification-opened', notification: $this->selectedNotification);
    }

    public function closeModal(): void
    {
        $this->showNotificationModal = false;
        $this->selectedNotification = null;
    }

    /**
     * @return array<string, mixed>
     */
    private function formatNotification(DatabaseNotification $notification): array
    {
        return [
            'id' => $notification->id,
            'title' => $notification->data['title'] ?? 'Notificación',
            'message' => $notification->data['message'] ?? '',
            'url' => $notification->data['url'] ?? null,
            'icon' => $notification->data['icon'] ?? 'bell',
            'created_at_human' => $notification->created_at?->diffForHumans(),
        ];
    }

    public function render()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(20);

        return view('livewire.notification-list', [
            'notifications' => $notifications,
        ]);
    }
}
