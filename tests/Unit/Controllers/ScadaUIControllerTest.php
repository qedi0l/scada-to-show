<?php

namespace Tests\Unit\Controllers;

use App\Models\MnemoSchema;
use App\Models\MnemoSchemaNode;
use App\Models\MnemoSchemaNodeOptions;
use App\Repositories\SchemaRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScadaUIControllerTest extends TestCase
{
    use RefreshDatabase;

    public string $urlHost = '/api/scada-ui/api/v1/scada/ui';

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_get_data_by_schema_name_route_returns_schema_data()
    {
        $schema = MnemoSchema::factory()->create();
        $node = MnemoSchemaNode::factory()->create(['schema_id' => $schema->id]);
        MnemoSchemaNodeOptions::factory()->create(['node_id' => $node->id]);

        $response = $this->getJson("$this->urlHost/get/data/$schema->name");

        $response->assertStatus(200)
            ->assertJsonStructure(['Schema'])
            ->assertJsonFragment(['name' => $schema->name]);
    }

    public function test_get_data_by_schema_name_route_returns_404_if_not_found()
    {
        $response = $this->getJson("$this->urlHost/get/data/non-existent-schema");

        $response->assertStatus(404);
    }

    public function test_get_node_params_by_schema_name_route_returns_node_params()
    {
        $schema = MnemoSchema::factory()->create();
        $node = MnemoSchemaNode::factory()->create(['schema_id' => $schema->id]);
        MnemoSchemaNodeOptions::factory()->create(['node_id' => $node->id]);

        $response = $this->get("$this->urlHost/get/nodeData/$schema->name");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
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

    public function test_get_node_params_by_schema_name_route_returns_empty_if_no_nodes()
    {
        $schema = MnemoSchema::factory()->create();

        $response = $this->get("$this->urlHost/get/nodeData/$schema->name");

        $response->assertStatus(200)
            ->assertJsonFragment(['nodes' => []]);
    }
//
    public function test_get_signals_of_all_schemas_route_returns_signals()
    {
        $schema = MnemoSchema::factory()->create();
        $node = MnemoSchemaNode::factory()->create(['schema_id' => $schema->id]);
        MnemoSchemaNodeOptions::factory()->create(['node_id' => $node->id]);

        $response = $this->get("$this->urlHost/get/signals");

        $response->assertStatus(200)
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

    public function test_get_signals_of_all_schemas_route_returns_404_if_no_signals()
    {
        $response = $this->get("$this->urlHost/get/signals");

        $response->assertStatus(404);
    }

    public function test_get_signals_of_single_schema_route_returns_signals_for_single_schema()
    {
        $schema = MnemoSchema::factory()->create();
        $node = MnemoSchemaNode::factory()->create(['schema_id' => $schema->id]);
        MnemoSchemaNodeOptions::factory()->create(['node_id' => $node->id]);

        $response = $this->get("$this->urlHost/get/signals/$schema->name");

        $response->assertStatus(200)
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

    public function test_get_signals_of_single_schema_route_returns_404_if_not_found()
    {
        $response = $this->get("$this->urlHost/get/signals/non-existent-schema");

        $response->assertStatus(404);
    }

    public function test_get_schema_titles_route_returns_schema_titles()
    {
        MnemoSchema::factory()->count(3)->create();

        $response = $this->get("$this->urlHost/get/schema/titles");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'schemas' => [
                    '*' => [
                        'name',
                        'title'
                    ]
                ]
            ]);
    }

    public function test_get_schema_titles_route_returns_empty_if_no_schemas()
    {
        $response = $this->get("$this->urlHost/get/schema/titles");

        $response->assertStatus(200)
            ->assertExactJson(['schemas' => []]);
    }

    public function test_get_node_available_signals_route_returns_signals()
    {
        $node = MnemoSchemaNode::factory()->create();
        MnemoSchemaNodeOptions::factory()->create([
            'node_id' => $node->id,
            'hardware_code' => 1
        ]);

        $response = $this->getJson("$this->urlHost/get/node/available/signals/$node->id");

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'title',
                    'parameterCode',
                    'hardwareCode',
                    'description'
                ]
            ]);
    }

    public function test_get_node_available_signals_route_returns_404_if_node_not_found()
    {
        $response = $this->getJson("$this->urlHost/get/node/available/signals/999");

        $response->assertStatus(200);
    }

    public function test_get_node_available_signals_route_returns_empty_if_no_signals()
    {
        $node = MnemoSchemaNode::factory()->create();

        $response = $this->getJson("$this->urlHost/get/node/available/signals/$node->id");


        $response->assertStatus(200)
            ->assertExactJson([]);
    }
}
