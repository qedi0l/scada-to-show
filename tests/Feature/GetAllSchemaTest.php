<?php

namespace Tests\Feature;

use App\Models\MnemoSchema;
use App\Models\MnemoSchemaLine;
use App\Models\MnemoSchemaLineAppearance;
use App\Models\MnemoSchemaLineOptions;
use App\Models\MnemoSchemaNode;
use App\Models\MnemoSchemaNodeAppearance;
use App\Models\MnemoSchemaNodeGeometry;
use App\Models\MnemoSchemaNodeOptions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetAllSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_mnemo_schemas()
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

        $response = $this->get('api/scada-ui/api/v1/scada/ui/get/all');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'title',
                    'name',
                    'nodes' => [
                        '*' => [
                            'id',
                            'title',
                            'type_title',
                            'type_id',
                            'type',
                            'service_type',
                            'group',
                            'options' => [
                                'appearance' => [
                                    'width',
                                    'height'
                                ],
                                'geometry' => [
                                    'x',
                                    'y',
                                    'rotate',
                                ],
                                'z_index',
                                'parent_id',
                                'hardware_code',
                                'parameter_code',
                            ],
                        ],
                    ],
                    'service_nodes' => [
                        '*' => [
                            'id',
                            'title',
                            'type_title',
                            'type_id',
                            'type',
                            'group',
                            'options' => [
                                'appearance' => [
                                    'width',
                                    'height'
                                ],
                                'geometry' => [
                                    'x',
                                    'y',
                                    'rotate',
                                ],
                                'z_index',
                                'parent_id',
                                'hardware_code',
                                'parameter_code',
                            ],
                        ],
                    ],
                    'lines' => [
                        '*' => [
                            'first_node',
                            'second_node',
                            'options' => [
                                'label',
                                'appearance' => [
                                    'color',
                                    'opacity',
                                    'width'
                                ],
                            ],
                        ],
                    ],
                ],
            ]);
    }
}
