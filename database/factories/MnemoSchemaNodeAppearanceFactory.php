<?php

namespace Database\Factories;

use App\Models\MnemoSchemaNode;
use App\Models\MnemoSchemaNodeAppearance;
use Illuminate\Database\Eloquent\Factories\Factory;

class MnemoSchemaNodeAppearanceFactory extends Factory
{
    protected $model = MnemoSchemaNodeAppearance::class;

    public function definition(): array
    {
        return [
            'node_id' => MnemoSchemaNode::factory(),
            'width' => $this->faker->numberBetween(10, 100),
            'height' => $this->faker->numberBetween(10, 100),
            'svg_url' => 'url',
            'min_svg' => 'url',
        ];
    }
}
