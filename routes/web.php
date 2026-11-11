<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\AssetList;
use App\Livewire\CreateTicket;
use App\Livewire\KnowledgeList;
use App\Livewire\NotificationList;
use App\Livewire\ReportDashboard;
use App\Livewire\SettingsManager;
use App\Livewire\TicketDetail;
use App\Livewire\TicketList;
use App\Livewire\UserList;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/tickets/create', CreateTicket::class)->name('tickets.create');
    Route::get('/tickets', TicketList::class)->name('tickets.index');
    Route::get('/tickets/{ticket}', TicketDetail::class)->name('tickets.show');

    Route::get('/assets', AssetList::class)->name('assets.index');
    Route::get('/users', UserList::class)->name('users.index');
    Route::get('/knowledge', KnowledgeList::class)->name('knowledge.index');
    Route::get('/reports', ReportDashboard::class)->name('reports.index');
    Route::get('/notifications', NotificationList::class)->name('notifications.index');
    Route::get('/settings', SettingsManager::class)->name('settings.index');
});

require __DIR__.'/auth.php';
