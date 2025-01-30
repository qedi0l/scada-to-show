<?php

namespace Database\Factories;

use App\Models\MnemoSchemaProject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MnemoSchemaProject>
 */
class MnemoSchemaProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => implode(' ', $this->faker->words()),
            'description' => $this->faker->text(),
            'short_title' => $this->faker->word(),
        ];
    }
}
