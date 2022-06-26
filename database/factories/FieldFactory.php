<?php

namespace Database\Factories;

use App\Enums\FieldType;
use App\Models\Subscriber;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Field>
 */
class FieldFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->name,
            'type' => $this->faker->randomElement(
                array_column(FieldType::cases(), 'value')
            ),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];
    }
}
