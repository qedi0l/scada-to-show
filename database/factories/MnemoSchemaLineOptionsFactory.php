<?php

namespace Database\Factories;

use App\Models\MnemoSchemaLine;
use App\Models\MnemoSchemaLineArrowType;
use App\Models\MnemoSchemaLineOptions;
use App\Models\MnemoSchemaLineType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MnemoSchemaLineOptions>
 */
class MnemoSchemaLineOptionsFactory extends Factory
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
            'type_id' => MnemoSchemaLineType::factory(),
            'text' => $this->faker->word,
            'first_arrow' => MnemoSchemaLineArrowType::factory(),
            'second_arrow' => MnemoSchemaLineArrowType::factory()
        ];
    }
}
