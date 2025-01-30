<?php

namespace Database\Factories;

use App\Models\MnemoSchema;
use App\Models\MnemoSchemaLine;
use App\Models\MnemoSchemaNode;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MnemoSchemaLine>
 */
class MnemoSchemaLineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'schema_id' => MnemoSchema::factory(),
            'first_node' => function (array $attributes) {
                $node = MnemoSchemaNode::query()
                    ->where(['schema_id' => $attributes['schema_id']])
                    ->inRandomOrder()
                    ->first();
                return $node ? $node->getKey() : MnemoSchemaNode::factory();
            },
            'second_node' => function (array $attributes) {
                $node = MnemoSchemaNode::query()
                    ->where(['schema_id' => $attributes['schema_id']])
                    ->inRandomOrder()
                    ->first();
                return $node ? $node->getKey() : MnemoSchemaNode::factory();
            },
            'source_position' => $this->faker->numberBetween(1, 4),
            'target_position' => $this->faker->numberBetween(1, 4)
        ];
    }
}
