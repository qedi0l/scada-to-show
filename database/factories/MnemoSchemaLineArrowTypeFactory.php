<?php

namespace Database\Factories;

use App\Models\MnemoSchemaLineArrowType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MnemoSchemaLineArrowType>
 */
class MnemoSchemaLineArrowTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'arrow_type_title' => $this->faker->word
        ];
    }
}
