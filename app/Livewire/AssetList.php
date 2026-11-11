<?php

namespace App\Livewire;

use App\Models\Asset;
use App\Models\Location;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AssetList extends Component
{
    use WithPagination;

    /**
     * Estados disponibles según el ENUM de la tabla assets.
     */
    public const STATUSES = [
        'Operativo' => 'Operativo',
        'Con Falla' => 'Con Falla',
        'En Mantenimiento' => 'En Mantenimiento',
        'De Baja' => 'De Baja',
    ];

    /**
     * Tipos de activo disponibles según el ENUM de la tabla assets.
     */
    public const TYPES = [
        'PC' => 'PC',
        'LAP' => 'Laptop',
        'PROY' => 'Proyector',
        'IMP' => 'Impresora',
        'SCAN' => 'Escáner',
        'SERV' => 'Servidor',
        'SWIT' => 'Switch',
        'ROUT' => 'Router',
        'MON' => 'Monitor',
        'TEC' => 'Teclado',
        'MOU' => 'Mouse',
        'PAR' => 'Parlante',
        'WEB' => 'Cámara Web',
        'UPS' => 'UPS',
        'OTRO' => 'Otro',
    ];

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $status = '';

    #[Url]
    public string $type = '';

    /**
     * Solo administradores y técnicos pueden acceder al inventario.
     */
    public function mount(): void
    {
        $user = auth()->user();

        abort_unless($user && $user->can('assets.view'), 403);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingType(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'status', 'type']);
        $this->resetPage();
    }

    #[On('asset-saved')]
    public function refreshAssets(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $assets = Asset::query()
            ->with(['location', 'responsible'])
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('asset_code', 'like', '%'.$this->search.'%')
                        ->orWhere('brand', 'like', '%'.$this->search.'%')
                        ->orWhere('serial_number', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->type, function ($query) {
                $query->where('type', $this->type);
            })
            ->orderBy('asset_code')
            ->paginate(10);

        return view('livewire.asset-list', [
            'assets' => $assets,
            'statuses' => self::STATUSES,
            'types' => self::TYPES,
            'locations' => Location::orderBy('name')->get(),
            'users' => User::orderBy('name')->get(),
        ]);
    }
}
