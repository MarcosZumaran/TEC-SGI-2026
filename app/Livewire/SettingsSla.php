<?php

namespace App\Livewire;

use App\Models\BusinessHour;
use App\Models\SlaPolicy;
use App\Services\SlaScheduleSynchronizer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class SettingsSla extends Component
{
    public array $policies = [];

    public array $businessHours = [];

    public function mount(): void
    {
        $this->authorizeAdmin();

        SlaPolicy::ensureDefaults();
        BusinessHour::ensureDefaults();

        $this->loadPolicies();
        $this->loadBusinessHours();
    }

    public function savePolicies(): void
    {
        $this->authorizeAdmin();

        $validated = $this->validate([
            'policies' => ['required', 'array', 'size:4'],
            'policies.*.priority' => ['required', Rule::in(SlaPolicy::PRIORITIES)],
            'policies.*.response_hours' => ['required', 'numeric', 'min:0', 'max:9999'],
            'policies.*.resolution_hours' => ['required', 'numeric', 'min:0', 'max:9999'],
            'policies.*.is_active' => ['required', 'boolean'],
        ]);

        $defaults = collect(SlaPolicy::defaults())->keyBy('priority');

        DB::transaction(function () use ($validated, $defaults): void {
            foreach ($validated['policies'] as $row) {
                $policy = SlaPolicy::firstOrNew(['priority' => $row['priority']]);

                if (! $policy->exists) {
                    $policy->name = $defaults[$row['priority']]['name'] ?? 'SLA Prioridad '.$row['priority'];
                }

                $policy->response_hours = $row['response_hours'];
                $policy->resolution_hours = $row['resolution_hours'];
                $policy->is_active = (bool) $row['is_active'];
                $policy->save();
            }
        });

        $this->loadPolicies();

        Cache::flush();

        session()->flash('sla_message', 'Políticas SLA actualizadas correctamente.');
    }

    public function saveBusinessHours(): void
    {
        $this->authorizeAdmin();

        $validated = $this->validate([
            'businessHours' => ['required', 'array', 'size:7'],
            'businessHours.*.day_of_week' => ['required', 'integer', 'between:0,6'],
            'businessHours.*.start_time' => ['required', 'date_format:H:i'],
            'businessHours.*.end_time' => ['required', 'date_format:H:i'],
            'businessHours.*.is_active' => ['required', 'boolean'],
        ]);

        $hasErrors = false;

        foreach ($validated['businessHours'] as $index => $row) {
            if ((bool) $row['is_active'] && $row['end_time'] <= $row['start_time']) {
                $this->addError(
                    'businessHours.'.$index.'.end_time',
                    'La hora de fin debe ser mayor que la hora de inicio.'
                );
                $hasErrors = true;
            }
        }

        if ($hasErrors) {
            return;
        }

        DB::transaction(function () use ($validated): void {
            foreach ($validated['businessHours'] as $row) {
                BusinessHour::updateOrCreate(
                    ['day_of_week' => $row['day_of_week']],
                    [
                        'start_time' => $row['start_time'],
                        'end_time' => $row['end_time'],
                        'is_active' => (bool) $row['is_active'],
                    ]
                );
            }
        });

        app(SlaScheduleSynchronizer::class)->synchronize();

        $this->loadBusinessHours();

        Cache::flush();

        session()->flash('sla_message', 'Horarios de servicio actualizados correctamente.');
    }

    private function loadPolicies(): void
    {
        $existing = SlaPolicy::query()->get()->keyBy('priority');

        $this->policies = collect(SlaPolicy::PRIORITIES)
            ->map(function (string $priority) use ($existing): array {
                $policy = $existing->get($priority);

                return [
                    'priority' => $priority,
                    'response_hours' => (float) ($policy?->response_hours ?? 0),
                    'resolution_hours' => (float) ($policy?->resolution_hours ?? 0),
                    'is_active' => (bool) ($policy?->is_active ?? true),
                ];
            })
            ->values()
            ->all();
    }

    private function loadBusinessHours(): void
    {
        $existing = BusinessHour::query()->get()->keyBy('day_of_week');

        $this->businessHours = collect(BusinessHour::DAYS)
            ->map(function (string $label, int $day) use ($existing): array {
                $hour = $existing->get($day);

                return [
                    'day_of_week' => $day,
                    'label' => $label,
                    'start_time' => $hour ? Carbon::parse($hour->start_time)->format('H:i') : '08:00',
                    'end_time' => $hour ? Carbon::parse($hour->end_time)->format('H:i') : '17:00',
                    'is_active' => (bool) ($hour?->is_active ?? false),
                ];
            })
            ->values()
            ->all();
    }

    private function authorizeAdmin(): void
    {
        abort_unless(
            auth()->check() && auth()->user()->can('settings.manage'),
            403
        );
    }

    public function render()
    {
        return view('livewire.settings-sla', [
            'days' => BusinessHour::DAYS,
        ]);
    }
}
