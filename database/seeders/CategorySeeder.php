<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Crea las categorías base de incidencias.
     */
    public function run(): void
    {
        $categories = [
            'Internet y Redes',
            'Hardware (PC/Laptops)',
            'Impresoras y Escáneres',
            'Proyectores y Audio',
            'Software y Sistemas',
        ];

        foreach ($categories as $name) {
            Category::updateOrCreate(['name' => $name]);
        }
    }
}
