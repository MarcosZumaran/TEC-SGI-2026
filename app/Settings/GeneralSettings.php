<?php

namespace App\Settings;

use Beartropy\Settings\Facades\BeartropySettings;
use Beartropy\Settings\Models\Setting;
use Illuminate\Support\Facades\Cache;

class GeneralSettings
{
    public const GROUP = 'general';

    public const SITE_NAME = 'site_name';

    public const ITEMS_PER_PAGE = 'items_per_page';

    public const MAINTENANCE_MODE = 'maintenance_mode';

    /**
     * @var array<string, mixed>
     */
    public const DEFAULTS = [
        self::SITE_NAME => 'TEC-SGI 2026',
        self::ITEMS_PER_PAGE => 10,
        self::MAINTENANCE_MODE => false,
    ];

    /**
     * @var array<string, string>
     */
    private const TYPES = [
        self::SITE_NAME => 'text',
        self::ITEMS_PER_PAGE => 'number',
        self::MAINTENANCE_MODE => 'boolean',
    ];

    /**
     * @var array<string, string>
     */
    private const LABELS = [
        self::SITE_NAME => 'Nombre del sitio',
        self::ITEMS_PER_PAGE => 'Elementos por página',
        self::MAINTENANCE_MODE => 'Modo mantenimiento',
    ];

    /**
     * @var array<string, string>
     */
    private const DESCRIPTIONS = [
        self::SITE_NAME => 'Nombre visible en el título y encabezado del sistema.',
        self::ITEMS_PER_PAGE => 'Cantidad de registros que se muestran en cada página.',
        self::MAINTENANCE_MODE => 'Activa el aviso de mantenimiento para el sistema.',
    ];

    /**
     * @return array{site_name: string, items_per_page: int, maintenance_mode: bool}
     */
    public static function all(): array
    {
        return [
            self::SITE_NAME => (string) BeartropySettings::get(
                self::key(self::SITE_NAME),
                self::DEFAULTS[self::SITE_NAME]
            ),
            self::ITEMS_PER_PAGE => (int) BeartropySettings::get(
                self::key(self::ITEMS_PER_PAGE),
                self::DEFAULTS[self::ITEMS_PER_PAGE]
            ),
            self::MAINTENANCE_MODE => filter_var(
                BeartropySettings::get(
                    self::key(self::MAINTENANCE_MODE),
                    self::DEFAULTS[self::MAINTENANCE_MODE]
                ),
                FILTER_VALIDATE_BOOLEAN
            ),
        ];
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $settings = self::all();

        return $settings[$key] ?? $default ?? self::DEFAULTS[$key] ?? null;
    }

    public static function siteName(): string
    {
        return (string) self::get(self::SITE_NAME);
    }

    /**
     * @param  array<string, mixed>  $values
     */
    public static function save(array $values): void
    {
        foreach (array_keys(self::DEFAULTS) as $key) {
            if (! array_key_exists($key, $values)) {
                continue;
            }

            $setting = Setting::firstOrNew([
                'group' => self::GROUP,
                'key' => $key,
            ]);

            $setting->type = self::TYPES[$key];
            $setting->label = self::LABELS[$key];
            $setting->description = self::DESCRIPTIONS[$key];
            $setting->is_system = true;
            $setting->value = $values[$key];
            $setting->save();
        }

        Cache::flush();
    }

    private static function key(string $name): string
    {
        return self::GROUP.'.'.$name;
    }
}
