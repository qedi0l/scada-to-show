<?php

namespace Database\Seeders;

use App\Models\MnemoSchema;
use App\Models\MnemoSchemaLine;
use App\Models\MnemoSchemaLineAppearance;
use App\Models\MnemoSchemaLineOptions;
use App\Models\MnemoSchemaNode;
use App\Models\MnemoSchemaNodeAppearance;
use App\Models\MnemoSchemaNodeGeometry;
use App\Models\MnemoSchemaNodeOptions;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        MnemoSchema::factory()
            ->has(
                MnemoSchemaNode::factory()
                    ->has(MnemoSchemaNodeOptions::factory(), 'options')
                    ->has(MnemoSchemaNodeAppearance::factory(), 'appearance')
                    ->has(MnemoSchemaNodeGeometry::factory(), 'geometry'),
                'nodes'
            )
            ->has(
                MnemoSchemaLine::factory()
                    ->has(MnemoSchemaLineOptions::factory(), 'options')
                    ->has(MnemoSchemaLineAppearance::factory(), 'appearance'),
                'lines'
            )
            ->create();
    }
}
