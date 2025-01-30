<?php

namespace Database\Factories;

use App\Models\MnemoSchema;
use App\Models\MnemoSchemaNode;
use App\Models\MnemoSchemaNodeGroup;
use App\Models\MnemoSchemaNodeType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MnemoSchemaNode>
 */
class MnemoSchemaNodeFactory extends Factory
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
            'title' => $this->faker->word,
            'type_id' => MnemoSchemaNodeType::factory(),
            'group_id' => MnemoSchemaNodeGroup::factory(),
        ];
    }
}
