<?php

namespace Tests\Feature;

use App\Models\MnemoSchema;
use App\Models\MnemoSchemaNode;
use App\Models\MnemoSchemaNodeCommand;
use App\Models\MnemoSchemaNodeOptions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class SCADAUITest extends TestCase
{
    use RefreshDatabase;

    public function test_get_scada_node_data()
    {
        $mnemoSchema = MnemoSchema::factory()->create();

        $response = $this->get("api/scada-ui/api/v1/scada/ui/get/nodeData/$mnemoSchema->name");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'title',
                'nodes' => [
                    '*' => [
                        'id',
                        'title',
                        'options' => [
                            '*' => [
                                'parameter_code',
                                'hardware_code'
                            ]
                        ]
                    ]
                ]
            ]);
    }

    public function test_get_mnemo_schema_data()
    {
        $mnemoSchema = MnemoSchema::factory()->create();

        $response = $this->get("api/scada-ui/api/v1/scada/ui/get/data/$mnemoSchema->name");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'Schema' => [
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

    public function test_get_schema_names_and_signals_structure()
    {
        MnemoSchema::factory()
            ->has(
                MnemoSchemaNode::factory()
                    ->has(MnemoSchemaNodeOptions::factory(), 'options')
                    ->has(MnemoSchemaNodeCommand::factory(), 'commands'),
                'nodes'
            )
            ->create();

        $response = $this->get('/api/scada-ui/api/v1/scada/ui/get/signals');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'schemas' => [
                    '*' => [
                        "schema_title",
                        "schema_name",
                        "signals" => [
                            '*' => [
                                'hardware_code',
                                'parameter_code',
                                'node_id'
                            ],
                        ],
                    ],
                ],
            ]);
    }

    public function test_get_signals_of_schema()
    {
        $schema = MnemoSchema::factory()->create();
        $node = MnemoSchemaNode::factory()->create([
            'schema_id' => $schema->id
        ]);
        MnemoSchemaNodeOptions::factory()->create([
            'node_id' => $node->id
        ]);
        $response = $this->get("/api/scada-ui/api/v1/scada/ui/get/signals/{$schema->name}");
        $signals = $response['schemas'][0]['signals'];

        $response->assertStatus(200)
            ->assertJson([
                'schemas' => [
                    [
                        'schema_title' => $schema->title,
                        'schema_name' => $schema->name,
                        'signals' => $signals
                    ]
                ]
            ]);
    }

    public function test_get_schema_titles()
    {
        MnemoSchema::factory()->count(3)->create();

        $response = $this->get('/api/scada-ui/api/v1/scada/ui/get/schema/titles');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'schemas' => [
                    '*' => [
                        'title',
                        'name'
                    ]
                ]
            ]);
    }

    public function test_get_schema_projects()
    {
        $response = $this->get('/api/scada-ui/api/v1/scada/ui/get/projects');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'title',
                    'short_title',
                    'description',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function test_get_node_hierarchy_when_parent_id_0()
    {
        $mnemoSchema = MnemoSchema::factory()->create();


        $response = $this->json('GET', "/api/scada-ui/api/v1/scada/ui/get/nodeHierarchy/$mnemoSchema->name/0");
        $response->assertStatus(200);


        $response->assertJsonStructure([
            '*' => [
                'id',
                'title',
                'parent_id',
                'min_svg',
                'type',
                'isHasChildren'
            ]
        ]);
    }

    public function test_get_node_hierarchy_when_parent_id_not_0()
    {
        MnemoSchema::factory()->create();

        $response = $this->json('GET', '/api/scada-ui/api/v1/scada/ui/get/nodeHierarchy/35BsppmhMn/1');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            '*' => [
                'id',
                'title',
                'parent_id',
                'min_svg',
                'type',
                'isHasChildren'
            ]
        ]);
    }


}
