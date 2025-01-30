<?php

namespace Database\Factories;

use App\Models\MnemoSchemaNode;
use App\Models\MnemoSchemaNodeGeometry;
use Illuminate\Database\Eloquent\Factories\Factory;

class MnemoSchemaNodeGeometryFactory extends Factory
{
    protected $model = MnemoSchemaNodeGeometry::class;

    public function definition(): array
    {
        return [
            'node_id' => MnemoSchemaNode::factory(),
            'x' => $this->faker->numberBetween(0, 100),
            'y' => $this->faker->numberBetween(0, 100),
            'rotation' => $this->faker->numberBetween(0, 359),
        ];
    }
}
