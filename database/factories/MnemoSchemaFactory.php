<?php

namespace Database\Factories;

use App\Models\MnemoSchema;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MnemoSchema>
 */
class MnemoSchemaFactory extends Factory
{
    protected $model = MnemoSchema::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->uuid(),
            'title' => $this->faker->word,
            'is_active' => true,
            'default' => false,
        ];
    }
}
