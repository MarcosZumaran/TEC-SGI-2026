<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
    protected $model = Location::class;

    public function definition(): array
    {
        return [
            'site_id' => Site::factory(),
            'code' => strtoupper($this->faker->unique()->lexify('LOC-???')),
            'name' => $this->faker->word() . ' ' . $this->faker->numberBetween(1, 100),
        ];
    }
}
