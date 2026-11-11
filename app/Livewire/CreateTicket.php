<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Ticket;
use App\Models\Location;
use App\Models\Category;
use App\Models\Asset;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class CreateTicket extends Component
{
    public $location_id = '';
    public $category_id = '';
    public $asset_id = '';
    public $description = '';
    public $priority = 'Media';

    public function mount()
    {
        // No cargamos datos aquí para no sobrecargar, lo haremos en la vista
    }

    protected function rules()
    {
        return [
            'location_id' => 'required|exists:locations,id',
            'category_id' => 'required|exists:categories,id',
            'asset_id' => 'nullable|exists:assets,id',
            'description' => 'required|string|min:10|max:1000',
            'priority' => 'required|in:Baja,Media,Alta,Critica',
        ];
    }

    public function save()
    {
        $this->validate();

        // Usamos una transacción para evitar duplicidad en el ticket_number
        // si dos usuarios guardan al mismo tiempo.
        DB::transaction(function () {
            // Generar el número de ticket: TKT-2026-0001
            $year = date('Y');
            $lastTicket = Ticket::whereYear('created_at', $year)->lockForUpdate()->count();
            $nextNumber = str_pad($lastTicket + 1, 4, '0', STR_PAD_LEFT);
            $ticketNumber = "TKT-{$year}-{$nextNumber}";

            Ticket::create([
                'ticket_number' => $ticketNumber,
                'reporter_id' => auth()->id(),
                'location_id' => $this->location_id,
                'category_id' => $this->category_id,
                'asset_id' => $this->asset_id ?: null, // Si está vacío, guardar null
                'description' => $this->description,
                'priority' => $this->priority,
                'status' => 'Abierto',
                'waiting_reason' => 'none',
            ]);
        });

        session()->flash('message', 'Ticket creado exitosamente.');

        // Limpiar el formulario después de guardar
        $this->reset(['location_id', 'category_id', 'asset_id', 'description', 'priority']);

        // Redirigir al dashboard o a la lista de tickets
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.create-ticket', [
            'locations' => Location::orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
            'assets' => Asset::orderBy('asset_code')->get(),
        ]);
    }
}
