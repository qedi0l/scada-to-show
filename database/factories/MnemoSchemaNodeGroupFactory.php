<?php

namespace Database\Factories;

use App\Models\MnemoSchemaNodeGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class MnemoSchemaNodeGroupFactory extends Factory
{
    protected $model = MnemoSchemaNodeGroup::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word,
            'svg_url' => 'svr_url',
        ];
    }
}
