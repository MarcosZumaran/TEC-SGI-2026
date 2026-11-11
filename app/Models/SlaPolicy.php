<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlaPolicy extends Model
{
    public const PRIORITIES = ['Baja', 'Media', 'Alta', 'Critica'];

    protected $fillable = [
        'name',
        'priority',
        'response_hours',
        'resolution_hours',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'priority' => 'string',
            'response_hours' => 'decimal:2',
            'resolution_hours' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function defaults(): array
    {
        return [
            [
                'name' => 'SLA Prioridad Baja',
                'priority' => 'Baja',
                'response_hours' => 8,
                'resolution_hours' => 48,
            ],
            [
                'name' => 'SLA Prioridad Media',
                'priority' => 'Media',
                'response_hours' => 4,
                'resolution_hours' => 24,
            ],
            [
                'name' => 'SLA Prioridad Alta',
                'priority' => 'Alta',
                'response_hours' => 2,
                'resolution_hours' => 8,
            ],
            [
                'name' => 'SLA Prioridad Crítica',
                'priority' => 'Critica',
                'response_hours' => 1,
                'resolution_hours' => 4,
            ],
        ];
    }

    public static function ensureDefaults(): void
    {
        foreach (self::defaults() as $policy) {
            self::query()->firstOrCreate(
                ['priority' => $policy['priority']],
                $policy + ['is_active' => true],
            );
        }
    }

    public static function forPriority(string $priority): ?self
    {
        return self::query()
            ->where('priority', $priority)
            ->where('is_active', true)
            ->first();
    }
}
