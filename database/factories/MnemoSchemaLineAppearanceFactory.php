<?php

namespace Database\Factories;

use App\Models\MnemoSchemaLine;
use App\Models\MnemoSchemaLineAppearance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MnemoSchemaLineAppearance>
 */
class MnemoSchemaLineAppearanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'line_id' => MnemoSchemaLine::factory(),
            'color' => $this->faker->hexColor,
            'opacity' => $this->faker->numberBetween(0, 100),
            'width' => $this->faker->numberBetween(0, 20)
        ];
    }
}
