<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Location;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition(): array
    {
        return [
            'ticket_number' => 'TKT-' . date('Y') . '-' . $this->faker->unique()->numerify('####'),
            'reporter_id' => User::factory(),
            'technician_id' => null,
            'asset_id' => null,
            'location_id' => Location::factory(),
            'category_id' => Category::factory(),
            'description' => $this->faker->sentence(),
            'priority' => $this->faker->randomElement(['Baja', 'Media', 'Alta', 'Critica']),
            'status' => 'Abierto',
            'waiting_reason' => 'none',
        ];
    }
}
