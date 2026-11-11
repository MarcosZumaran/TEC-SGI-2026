<?php

namespace App\Livewire;

use App\Models\Asset;
use App\Models\Location;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class AssetForm extends Component
{
    public ?int $assetId = null;

    public string $asset_code = '';

    public string $type = 'PC';

    public $parent_id = '';

    public $brand = '';

    public $model = '';

    public $serial_number = '';

    public $location_id = '';

    public $responsible_user_id = '';

    public string $status = 'Operativo';

    public $acquisition_date = '';

    public $observations = '';

    public $processor = '';

    public $ram = '';

    public $disk = '';

    public $os = '';

    public $ip_address = '';

    public $mac_address = '';

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

    public const STATUSES = [
        'Operativo' => 'Operativo',
        'Con Falla' => 'Con Falla',
        'En Mantenimiento' => 'En Mantenimiento',
        'De Baja' => 'De Baja',
    ];

    public function mount(?int $assetId = null): void
    {
        $user = auth()->user();

        abort_unless($user && $user->can('assets.manage'), 403);

        if ($assetId === null) {
            return;
        }

        $asset = Asset::with('specs')->findOrFail($assetId);

        $this->assetId = $asset->id;
        $this->fill([
            'asset_code' => $asset->asset_code,
            'type' => $asset->type,
            'parent_id' => $asset->parent_id,
            'brand' => $asset->brand ?? '',
            'model' => $asset->model ?? '',
            'serial_number' => $asset->serial_number ?? '',
            'location_id' => $asset->location_id,
            'responsible_user_id' => $asset->responsible_user_id,
            'status' => $asset->status,
            'acquisition_date' => $asset->acquisition_date?->format('Y-m-d') ?? '',
            'observations' => $asset->observations ?? '',
            'processor' => $asset->specs?->processor ?? '',
            'ram' => $asset->specs?->ram ?? '',
            'disk' => $asset->specs?->disk ?? '',
            'os' => $asset->specs?->os ?? '',
            'ip_address' => $asset->specs?->ip_address ?? '',
            'mac_address' => $asset->specs?->mac_address ?? '',
        ]);
    }

    protected function rules(): array
    {
        $rules = [
            'asset_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('assets', 'asset_code')->ignore($this->assetId),
            ],
            'type' => ['required', Rule::in(array_keys(self::TYPES))],
            'parent_id' => ['nullable', 'integer', 'exists:assets,id'],
            'brand' => ['nullable', 'string', 'max:100'],
            'model' => ['nullable', 'string', 'max:100'],
            'serial_number' => ['nullable', 'string', 'max:100'],
            'location_id' => ['required', 'integer', 'exists:locations,id'],
            'responsible_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'status' => ['required', Rule::in(array_keys(self::STATUSES))],
            'acquisition_date' => ['nullable', 'date'],
            'observations' => ['nullable', 'string'],
        ];

        if (in_array($this->type, ['PC', 'LAP'], true)) {
            $rules = array_merge($rules, [
                'processor' => ['nullable', 'string', 'max:150'],
                'ram' => ['nullable', 'string', 'max:50'],
                'disk' => ['nullable', 'string', 'max:100'],
                'os' => ['nullable', 'string', 'max:100'],
                'ip_address' => ['nullable', 'ip'],
                'mac_address' => ['nullable', 'string', 'max:17'],
            ]);
        }

        return $rules;
    }

    public function save(): void
    {
        $this->normalizeNullableFields();
        $this->validate();

        $isEditing = $this->assetId !== null;
        $isComputer = in_array($this->type, ['PC', 'LAP'], true);

        DB::transaction(function () use ($isEditing, $isComputer) {
            $assetData = [
                'asset_code' => trim($this->asset_code),
                'type' => $this->type,
                'parent_id' => $this->parent_id,
                'brand' => $this->brand,
                'model' => $this->model,
                'serial_number' => $this->serial_number,
                'location_id' => $this->location_id,
                'responsible_user_id' => $this->responsible_user_id,
                'status' => $this->status,
                'acquisition_date' => $this->acquisition_date,
                'observations' => $this->observations,
            ];

            if ($isEditing) {
                $asset = Asset::findOrFail($this->assetId);
                $asset->update($assetData);
            } else {
                $asset = Asset::create($assetData);
            }

            if ($isComputer) {
                $asset->specs()->updateOrCreate([], [
                    'processor' => $this->processor,
                    'ram' => $this->ram,
                    'disk' => $this->disk,
                    'os' => $this->os,
                    'ip_address' => $this->ip_address,
                    'mac_address' => $this->mac_address,
                ]);
            } else {
                $asset->specs()->delete();
            }
        });

        if (! $isEditing) {
            $this->resetForm();
        }

        session()->flash(
            'message',
            $isEditing ? 'Equipo actualizado exitosamente.' : 'Equipo registrado exitosamente.'
        );

        $this->dispatch('asset-saved');
    }

    protected function normalizeNullableFields(): void
    {
        $fields = [
            'parent_id',
            'brand',
            'model',
            'serial_number',
            'location_id',
            'responsible_user_id',
            'acquisition_date',
            'observations',
            'processor',
            'ram',
            'disk',
            'os',
            'ip_address',
            'mac_address',
        ];

        foreach ($fields as $field) {
            $this->{$field} = filled($this->{$field}) ? $this->{$field} : null;
        }
    }

    protected function resetForm(): void
    {
        $this->reset([
            'asset_code',
            'parent_id',
            'brand',
            'model',
            'serial_number',
            'location_id',
            'responsible_user_id',
            'acquisition_date',
            'observations',
            'processor',
            'ram',
            'disk',
            'os',
            'ip_address',
            'mac_address',
        ]);

        $this->resetValidation();
        $this->type = 'PC';
        $this->status = 'Operativo';
    }

    public function render()
    {
        return view('livewire.asset-form', [
            'types' => self::TYPES,
            'statuses' => self::STATUSES,
            'locations' => Location::orderBy('name')->get(),
            'users' => User::orderBy('name')->get(),
            'assets' => Asset::query()
                ->when($this->assetId, fn ($query) => $query->where('id', '!=', $this->assetId))
                ->orderBy('asset_code')
                ->get(),
        ]);
    }
}
