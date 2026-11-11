<?php

namespace App\Livewire;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Collection;
use Livewire\Component;

class NotificationBell extends Component
{
    public int $unreadCount = 0;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $selectedNotification = null;

    public bool $showNotificationModal = false;

    public function mount(): void
    {
        $this->refreshUnreadCount();
    }

    public function getNotificationsProperty(): Collection
    {
        $user = auth()->user();

        if (! $user) {
            return collect();
        }

        return $user->notifications()->take(10)->get();
    }

    public function markAsRead(string $id): void
    {
        $user = auth()->user();

        abort_if($user === null, 403);

        $notification = $user->notifications()
            ->whereKey($id)
            ->first();

        abort_if($notification === null, 404);

        $notification->markAsRead();

        $this->refreshUnreadCount();
        $this->selectedNotification = $this->formatNotification($notification);
        $this->showNotificationModal = true;

        $this->dispatch('notification-opened', notification: $this->selectedNotification);
    }

    public function markAllAsRead(): void
    {
        $user = auth()->user();

        abort_if($user === null, 403);

        $user->unreadNotifications->markAsRead();

        $this->refreshUnreadCount();

        $this->dispatch('notifications-read');
    }

    public function closeModal(): void
    {
        $this->showNotificationModal = false;
        $this->selectedNotification = null;
    }

    private function refreshUnreadCount(): void
    {
        $user = auth()->user();

        $this->unreadCount = $user
            ? $user->unreadNotifications()->count()
            : 0;
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
        return view('livewire.notification-bell');
    }
}
