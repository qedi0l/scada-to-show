<?php

namespace Database\Factories;

use App\Models\MnemoSchema;
use App\Models\MnemoSchemaNode;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MnemoSchemaNode>
 */
class MnemoSchemaNodeLinkFactory extends Factory
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
            'schema_id' => MnemoSchema::factory(),
        ];
    }
}
