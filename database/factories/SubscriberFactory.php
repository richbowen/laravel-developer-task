<?php

namespace Database\Factories;

use App\Enums\SubscriberState;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscriber>
 */
class SubscriberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'state' => $this->faker->randomElement(
                array_column(SubscriberState::cases(), 'value')
            ),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];
    }
}
