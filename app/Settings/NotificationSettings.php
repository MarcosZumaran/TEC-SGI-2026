<?php

namespace App\Settings;

use Beartropy\Settings\Facades\BeartropySettings;
use Beartropy\Settings\Models\Setting;
use Illuminate\Support\Facades\Cache;

class NotificationSettings
{
    public const GROUP = 'notifications';

    /**
     * @var array<string, array{label: string, subject: string, body: string}>
     */
    public const TEMPLATES = [
        'ticket_created' => [
            'label' => 'Ticket creado',
            'subject' => 'Nuevo ticket {{ticket_number}} registrado',
            'body' => "Hola {{reporter_name}},\n\nHemos recibido tu incidencia {{ticket_number}}. Nuestro equipo la revisará y te mantendrá informado.",
        ],
        'status_changed' => [
            'label' => 'Cambio de estado',
            'subject' => 'Actualización del ticket {{ticket_number}}',
            'body' => "Hola {{reporter_name}},\n\nEl estado de tu ticket {{ticket_number}} cambió a {{ticket_status}}.",
        ],
        'ticket_resolved' => [
            'label' => 'Ticket resuelto',
            'subject' => 'Ticket {{ticket_number}} resuelto',
            'body' => "Hola {{reporter_name}},\n\nTu ticket {{ticket_number}} fue resuelto. Si el problema persiste, puedes responder a este mensaje.",
        ],
    ];

    /**
     * @return array<string, array{subject: string, body: string}>
     */
    public static function all(): array
    {
        $templates = [];

        foreach (array_keys(self::TEMPLATES) as $template) {
            $templates[$template] = self::get($template);
        }

        return $templates;
    }

    /**
     * @return array{subject: string, body: string}
     */
    public static function get(string $template): array
    {
        $default = self::TEMPLATES[$template] ?? self::TEMPLATES['ticket_created'];
        $value = BeartropySettings::get(self::key($template), $default);

        if (is_string($value)) {
            $decoded = json_decode($value, true);

            if (is_array($decoded)) {
                $value = $decoded;
            }
        }

        return [
            'subject' => (string) ($value['subject'] ?? $default['subject']),
            'body' => (string) ($value['body'] ?? $default['body']),
        ];
    }

    public static function save(string $template, string $subject, string $body): void
    {
        if (! array_key_exists($template, self::TEMPLATES)) {
            return;
        }

        $setting = Setting::firstOrNew([
            'group' => self::GROUP,
            'key' => $template,
        ]);

        $setting->type = 'json';
        $setting->label = self::TEMPLATES[$template]['label'];
        $setting->description = 'Plantilla de notificación: '.self::TEMPLATES[$template]['label'];
        $setting->is_system = true;
        $setting->value = [
            'subject' => $subject,
            'body' => $body,
        ];
        $setting->save();

        Cache::flush();
    }

    private static function key(string $template): string
    {
        return self::GROUP.'.'.$template;
    }
}
