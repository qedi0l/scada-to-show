<?php

namespace Database\Factories;

use App\Models\MnemoSchemaNode;
use App\Models\MnemoSchemaNodeCommand;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MnemoSchemaNodeCommand>
 */
class MnemoSchemaNodeCommandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'node_id' => MnemoSchemaNode::factory(),
            'parameter_code' => $this->faker->numberBetween(1, 2)
        ];
    }
}
