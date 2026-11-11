<?php

namespace App\Services;

use App\Models\BusinessHour;
use App\Models\SlaPolicy;
use App\Models\TecBusinessHoursSchedule;
use Illuminate\Support\Facades\DB;

class SlaScheduleSynchronizer
{
    public const SCHEDULE_NAME = 'Horario de Servicio TEC-SGI';

    public function synchronize(): TecBusinessHoursSchedule
    {
        SlaPolicy::ensureDefaults();
        BusinessHour::ensureDefaults();

        return DB::transaction(function (): TecBusinessHoursSchedule {
            $schedule = TecBusinessHoursSchedule::query()->firstOrCreate(
                ['name' => self::SCHEDULE_NAME],
                [
                    'description' => 'Horario laboral configurado desde el módulo de configuración.',
                    'timezone' => (string) config('app.timezone', 'UTC'),
                    'is_default' => true,
                    'is_active' => true,
                ],
            );

            TecBusinessHoursSchedule::query()
                ->whereKeyNot($schedule->getKey())
                ->where('is_default', true)
                ->update(['is_default' => false]);

            $schedule->forceFill([
                'timezone' => (string) config('app.timezone', 'UTC'),
                'is_default' => true,
                'is_active' => true,
            ])->save();

            $schedule->timeSlots()->delete();

            BusinessHour::query()
                ->where('is_active', true)
                ->orderBy('day_of_week')
                ->get()
                ->each(function (BusinessHour $businessHour) use ($schedule): void {
                    $schedule->timeSlots()->create([
                        'day_of_week' => $businessHour->day_of_week,
                        'start_time' => $businessHour->start_time,
                        'end_time' => $businessHour->end_time,
                    ]);
                });

            return $schedule->fresh('timeSlots');
        });
    }

    public function schedule(): TecBusinessHoursSchedule
    {
        $schedule = TecBusinessHoursSchedule::query()
            ->where('name', self::SCHEDULE_NAME)
            ->where('is_active', true)
            ->with('timeSlots')
            ->first();

        if ($schedule !== null && $schedule->timeSlots->isNotEmpty()) {
            return $schedule;
        }

        return $this->synchronize();
    }
}
