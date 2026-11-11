<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Ejecuta los seeders en orden de dependencias.
     */
    public function run(): void
    {
        $this->call([
            SiteSeeder::class,
            LocationSeeder::class,
            CategorySeeder::class,
            UserSeeder::class,
            PermissionSeeder::class,
        ]);
    }
}
