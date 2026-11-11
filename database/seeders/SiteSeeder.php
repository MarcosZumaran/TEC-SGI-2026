<?php

namespace Database\Seeders;

use App\Models\Site;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    /**
     * Crea la sede principal del Instituto.
     */
    public function run(): void
    {
        Site::updateOrCreate(
            ['name' => 'Sede Callería'],
            ['address' => 'Distrito de Callería, Coronel Portillo, Ucayali']
        );
    }
}
