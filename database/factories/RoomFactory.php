<?php

namespace Database\Factories;

use App\Models\Apartment;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'apartment_id' => Apartment::value('id'),
            'room_type_id' => RoomType::value('id'),
            'name' => fake()->text(10),
        ];
    }
}
