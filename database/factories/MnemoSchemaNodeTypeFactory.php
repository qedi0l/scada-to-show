<?php

namespace Database\Factories;

use App\Models\MnemoSchemaNodeType;
use App\Models\MnemoSchemaNodeTypeGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MnemoSchemaNodeType>
 */
class MnemoSchemaNodeTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->word(),
            'hardware_type' => $this->faker->word(),
            'service_type' => $this->faker->boolean,
            'title' => implode(' ', $this->faker->words()),
            'node_type_group_id' => MnemoSchemaNodeTypeGroup::factory(),
            'svg' => 'svg'
        ];
    }
}
