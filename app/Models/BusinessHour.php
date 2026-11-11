<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessHour extends Model
{
    public const DAYS = [
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miércoles',
        4 => 'Jueves',
        5 => 'Viernes',
        6 => 'Sábado',
        0 => 'Domingo',
    ];

    protected $fillable = [
        'day_of_week',
        'start_time',
        'end_time',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'day_of_week' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return array<int, array{start_time: string, end_time: string, is_active: bool}>
     */
    public static function defaults(): array
    {
        $weekday = ['start_time' => '08:00:00', 'end_time' => '17:00:00', 'is_active' => true];
        $weekend = ['start_time' => '08:00:00', 'end_time' => '12:00:00', 'is_active' => false];

        return [
            1 => $weekday,
            2 => $weekday,
            3 => $weekday,
            4 => $weekday,
            5 => $weekday,
            6 => $weekend,
            0 => $weekend,
        ];
    }

    public static function ensureDefaults(): void
    {
        foreach (self::defaults() as $dayOfWeek => $hours) {
            self::query()->firstOrCreate(
                ['day_of_week' => $dayOfWeek],
                $hours,
            );
        }
    }
}
