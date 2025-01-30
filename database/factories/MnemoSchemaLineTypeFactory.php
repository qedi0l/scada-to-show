<?php

namespace Database\Factories;

use App\Models\MnemoSchemaLineType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MnemoSchemaLineType>
 */
class MnemoSchemaLineTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->unique()->word
        ];
    }
}
