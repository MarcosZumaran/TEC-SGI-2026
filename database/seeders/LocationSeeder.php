<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Site;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Crea las ubicaciones internas asociadas a la Sede Callería.
     */
    public function run(): void
    {
        // La sede debe existir previamente (SiteSeeder se ejecuta antes).
        $site = Site::where('name', 'Sede Callería')->firstOrFail();

        $locations = [
            ['code' => 'LAB1', 'name' => 'Laboratorio 1'],
            ['code' => 'LAB2', 'name' => 'Laboratorio 2'],
            ['code' => 'DIREC', 'name' => 'Dirección'],
            ['code' => 'SECRE', 'name' => 'Secretaría'],
            ['code' => 'ADMIN', 'name' => 'Administración'],
            ['code' => 'SOPORTE', 'name' => 'Soporte TI'],
        ];

        foreach ($locations as $location) {
            Location::updateOrCreate(
                ['code' => $location['code']],
                [
                    'site_id' => $site->id,
                    'name' => $location['name'],
                ]
            );
        }
    }
}
