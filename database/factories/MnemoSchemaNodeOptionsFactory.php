<?php

namespace Database\Factories;

use App\Models\MnemoSchemaNode;
use App\Models\MnemoSchemaNodeOptions;
use Illuminate\Database\Eloquent\Factories\Factory;

class MnemoSchemaNodeOptionsFactory extends Factory
{
    protected $model = MnemoSchemaNodeOptions::class;

    public function definition(): array
    {
        return [
            'node_id' => MnemoSchemaNode::factory(),
            'parent_id' => function (array $attributes) {
                /** @var MnemoSchemaNode $node */
                $node = MnemoSchemaNode::query()->find($attributes['node_id']);
                $randomNeighbour = $node->neighbours()
                    ->inRandomOrder()
                    ->whereNot('id', $attributes['node_id'])
                    ->first();
                return $randomNeighbour?->getKey();
            },
            'hardware_code' => $this->faker->numberBetween(1, 15),
            'parameter_code' => $this->faker->numberBetween(1, 15),
            'z_index' => $this->faker->numberBetween(1, 100),
            'label' => $this->faker->word()
        ];
    }
}
