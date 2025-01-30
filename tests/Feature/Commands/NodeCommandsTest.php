<?php

namespace Tests\Feature\Commands;

use App\Enums\CommandType;
use App\Models\MnemoSchema;
use App\Models\MnemoSchemaLine;
use App\Models\MnemoSchemaLineAppearance;
use App\Models\MnemoSchemaLineOptions;
use App\Models\MnemoSchemaNode;
use App\Models\MnemoSchemaNodeAppearance;
use App\Models\MnemoSchemaNodeCommand;
use App\Models\MnemoSchemaNodeGeometry;
use App\Models\MnemoSchemaNodeGroup;
use App\Models\MnemoSchemaNodeLink;
use App\Models\MnemoSchemaNodeOptions;
use App\Models\MnemoSchemaNodeType;
use App\Models\Types\NodeTypeType;
use App\Services\CatalogServices\CatalogSignalService;
use App\Services\CatalogServices\Models\Signal;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class NodeCommandsTest extends BaseCommandTest
{
    use RefreshDatabase;

    public function test_add_label_to_node(): void
    {
        // Prepare
        $origin = ['label' => 'origin'];
        $changed = ['label' => 'changed'];
        $nodeOptions = MnemoSchemaNodeOptions::factory()->create($origin);
        $data = [
            'method_title' => CommandType::AddLabelToNode->value,
            'data' => [
                'node_id' => $nodeOptions->node_id,
                'label' => $changed['label'],
            ]
        ];

        // Execute
        $response = $this->json('POST', $this->commandExecuteUrl, $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas(MnemoSchemaNodeOptions::class, [
            'node_id' => $nodeOptions->node_id,
            'label' => $changed['label'],
        ]);
        $this->assertDatabaseMissing(MnemoSchemaNodeOptions::class, [
            'node_id' => $nodeOptions->node_id,
            'label' => $origin['label'],
        ]);

        // Undo
        $response = $this->json('POST', $this->commandUndoUrl . $nodeOptions->node->schema->name);
        $response->assertStatus(200);
        $this->assertDatabaseHas(MnemoSchemaNodeOptions::class, [
            'node_id' => $nodeOptions->node_id,
            'label' => $origin['label'],
        ]);
        $this->assertDatabaseMissing(MnemoSchemaNodeOptions::class, [
            'node_id' => $nodeOptions->node_id,
            'label' => $changed['label'],
        ]);
    }

    public function test_add_multiple_child_nodes(): void
    {
        // Prepare
        $catalogSignalService = new CatalogSignalService();
        /** @var Signal $hardware */
        $hardware = $catalogSignalService->getAllReadableSignals()->first();
        $signals = $catalogSignalService->getReadableSignalsByHardwareCode($hardware->transportHardwareId);

        $nodeTypes = MnemoSchemaNodeType::factory()->count($signals->count())->create();
        $node = MnemoSchemaNode::factory()
            //['hardware_code' => $hardware->transportHardwareId]
            ->has(MnemoSchemaNodeOptions::factory(['hardware_code' => $hardware->transportHardwareId]), 'options')
            ->has(MnemoSchemaNodeGeometry::factory(), 'geometry')
            ->create();
        $data = [
            'method_title' => CommandType::AddMultipleChildNodes->value,
            'data' => [
                'schema_name' => $node->schema->name,
                'node_id' => $node->getKey(),
                'node_types' => $nodeTypes->pluck('type')->toArray(),
                'signals' => $signals->pluck('transportSignalId')->toArray()
            ]
        ];

        // Execute
        $response = $this->json('POST', $this->commandExecuteUrl, $data);
        $response->assertStatus(200);
        $node = MnemoSchemaNode::query()->find($node->getKey());

        foreach ($data['data']['signals'] as $signal) {
            $this->assertDatabaseHas(MnemoSchemaNodeOptions::class, [
                'parent_id' => $node->getKey(),
                'hardware_code' => $node->options->hardware_code,
                'parameter_code' => $signal
            ]);
        }
        // Undo
    }

    public function test_add_node_to_schema(): void
    {
        // Prepare
        $schema = MnemoSchema::factory()->create();
        $group = MnemoSchemaNodeGroup::factory()->create();
        $nodeType = MnemoSchemaNodeType::query()->whereNot('type', NodeTypeType::Link->value)
            ->get()
            ->random();
        $nodeTitle = Str::random();

        // Execute
        $data = [
            'method_title' => CommandType::AddNodeToSchema->value,
            'data' => [
                'node' => [
                    'title' => $nodeTitle,
                    'schema_name' => $schema->name,
                    'group_id' => $group->getKey(),
                    'type' => $nodeType->type,
                    'options' => [
                        'appearance' => [
                            'width' => rand(1, 500),
                            'height' => rand(1, 500),
                            'svg_url' => 'svg/url',
                            'min_svg' => 'min/svg'
                        ],
                        'geometry' => [
                            'x' => rand(1, 500),
                            'y' => rand(1, 500)
                        ],
                        'parameter_code' => 1,
                        'hardware_code' => 1
                    ]
                ]
            ]
        ];
        $response = $this->json('POST', $this->commandExecuteUrl, $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas(MnemoSchemaNode::class, [
            'title' => $nodeTitle
        ]);
        $createdNode = MnemoSchemaNode::query()->where('title', $nodeTitle)->first();
        $this->assertDatabaseHas(MnemoSchemaNodeOptions::class, [
            'node_id' => $createdNode->getKey(),
        ]);
        $this->assertDatabaseHas(MnemoSchemaNodeAppearance::class, [
            'node_id' => $createdNode->getKey(),
        ]);
        $this->assertDatabaseHas(MnemoSchemaNodeGeometry::class, [
            'node_id' => $createdNode->getKey(),
        ]);

        // Undo
        $response = $this->json('POST', $this->commandUndoUrl . $schema->name);
        $response->assertStatus(200);
        $this->assertDatabaseMissing(MnemoSchemaNode::class, [
            'title' => $nodeTitle
        ]);
        $this->assertDatabaseMissing(MnemoSchemaNodeOptions::class, [
            'node_id' => $createdNode->getKey(),
        ]);
        $this->assertDatabaseMissing(MnemoSchemaNodeAppearance::class, [
            'node_id' => $createdNode->getKey(),
        ]);
        $this->assertDatabaseMissing(MnemoSchemaNodeGeometry::class, [
            'node_id' => $createdNode->getKey(),
        ]);
    }

    public function test_add_node_from_node_type_group()
    {
        // Prepare
        $schema = MnemoSchema::factory()->create();
        $nodeType = MnemoSchemaNodeType::factory()->create();
        $data = [
            'method_title' => CommandType::AddNodeFromNodeTypeGroup->value,
            'data' => [
                'schema_name' => $schema->name,
                'node_type' => $nodeType->type,
            ]
        ];

        // Execute
        $response = $this->json('POST', $this->commandExecuteUrl, $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas(MnemoSchemaNode::class, [
            'schema_id' => $schema->getKey(),
            'type_id' => $nodeType->getKey(),
        ]);
        // Undo
    }

    public function test_add_node_from_node_type_group_with_link()
    {
        // Prepare
        $schema = MnemoSchema::factory()->create();
        $linkedSchema = MnemoSchema::factory()->create();

        $linkNodeType = MnemoSchemaNodeType::query()->where('type', NodeTypeType::Link->value)->first();

        $data = [
            'method_title' => CommandType::AddNodeFromNodeTypeGroup->value,
            'data' => [
                'schema_name' => $schema->name,
                'node_type' => $linkNodeType->type,
                'node_link' => [
                    'schema_name' => $linkedSchema->name,
                ],
            ]
        ];

        // Execute
        $response = $this->json('POST', $this->commandExecuteUrl, $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas(MnemoSchemaNode::class, [
            'schema_id' => $schema->getKey(),
            'type_id' => $linkNodeType->getKey(),
        ]);

        $node = MnemoSchemaNode::query()
            ->where([
                'schema_id' => $schema->getKey(),
                'type_id' => $linkNodeType->getKey(),
            ])
            ->first();

        $this->assertDatabaseHas(MnemoSchemaNodeLink::class, [
            'node_id' => $node->getKey(),
            'schema_id' => $linkedSchema->getKey(),
        ]);
        // Undo
    }

    public function test_change_node_geometry(): void
    {
        // Prepare
        $origin = [
            'x' => rand(1, 500),
            'y' => rand(1, 500),
            'rotation' => rand(1, 359),
        ];
        $changed = [
            'x' => rand(1, 500),
            'y' => rand(1, 500),
            'rotation' => rand(1, 359),
        ];
        $nodeGeometry = MnemoSchemaNodeGeometry::factory()
            ->create($origin);
        $data = [
            'method_title' => CommandType::ChangeNodeGeometry->value,
            'data' => [
                'node_id' => $nodeGeometry->node_id,
                'x' => $changed['x'],
                'y' => $changed['y'],
                'rotate' => $changed['rotation'],
            ]
        ];

        // Execute
        $response = $this->json('POST', $this->commandExecuteUrl, $data);
        $response->assertStatus(200);
        $this->assertDatabaseMissing(MnemoSchemaNodeGeometry::class, [
            'node_id' => $nodeGeometry->node_id,
            'x' => $origin['x'],
            'y' => $origin['y'],
            'rotation' => $origin['rotation']
        ]);
        $this->assertDatabaseHas(MnemoSchemaNodeGeometry::class, [
            'node_id' => $nodeGeometry->node_id,
            'x' => $changed['x'],
            'y' => $changed['y'],
            'rotation' => $changed['rotation']
        ]);

        // Undo
        $response = $this->json('POST', $this->commandUndoUrl . $nodeGeometry->node->schema->name);
        $response->assertStatus(200);
        $this->assertDatabaseMissing(MnemoSchemaNodeGeometry::class, [
            'node_id' => $nodeGeometry->node_id,
            'x' => $changed['x'],
            'y' => $changed['y'],
            'rotation' => $changed['rotation']
        ]);
        $this->assertDatabaseHas(MnemoSchemaNodeGeometry::class, [
            'node_id' => $nodeGeometry->node_id,
            'x' => $origin['x'],
            'y' => $origin['y'],
            'rotation' => $origin['rotation']
        ]);
    }

    public function test_change_node_label()
    {
        // Prepare
        $originLabel = 'origin label';
        $changedLabel = 'changed label';

        $nodeOptions = MnemoSchemaNodeOptions::factory()
            ->create(['label' => $originLabel]);

        $data = [
            'method_title' => CommandType::ChangeNodeLabel->value,
            'data' => [
                'node_id' => $nodeOptions->node_id,
                'label' => $changedLabel
            ]
        ];

        // Execute
        $response = $this->json('POST', $this->commandExecuteUrl, $data);
        $response->assertStatus(200);
        $this->assertDatabaseMissing(MnemoSchemaNodeOptions::class, [
            'node_id' => $nodeOptions->node_id,
            'label' => $originLabel
        ]);
        $this->assertDatabaseHas(MnemoSchemaNodeOptions::class, [
            'node_id' => $nodeOptions->node_id,
            'label' => $changedLabel
        ]);

        // Undo
        $response = $this->json('POST', $this->commandUndoUrl . $nodeOptions->node->schema->name);
        $response->assertStatus(200);
        $this->assertDatabaseMissing(MnemoSchemaNodeOptions::class, [
            'node_id' => $nodeOptions->node_id,
            'label' => $changedLabel
        ]);
        $this->assertDatabaseHas(MnemoSchemaNodeOptions::class, [
            'node_id' => $nodeOptions->node_id,
            'label' => $originLabel
        ]);
    }

    public function test_change_node_link()
    {
        // Prepare
        $originSchema = MnemoSchema::factory()->create();
        $changedSchema = MnemoSchema::factory()->create();
        $nodeLink = MnemoSchemaNodeLink::factory()
            ->create(['schema_id' => $originSchema->getKey()]);
        $data = [
            'method_title' => CommandType::ChangeNodeLink->value,
            'data' => [
                'node_id' => $nodeLink->node_id,
                'link' => [
                    'schema_name' => $changedSchema->name,
                ],
            ]
        ];

        // Execute
        $response = $this->json('POST', $this->commandExecuteUrl, $data);
        $response->assertStatus(200);
        $this->assertDatabaseMissing(MnemoSchemaNodeLink::class, [
            'node_id' => $nodeLink->node_id,
            'schema_id' => $originSchema->getKey(),
        ]);
        $this->assertDatabaseHas(MnemoSchemaNodeLink::class, [
            'node_id' => $nodeLink->node_id,
            'schema_id' => $changedSchema->getKey(),
        ]);

        // Undo
        $response = $this->json('POST', $this->commandUndoUrl . $nodeLink->node->schema->name);
        $response->assertStatus(200);
        $this->assertDatabaseMissing(MnemoSchemaNodeLink::class, [
            'node_id' => $nodeLink->node_id,
            'schema_id' => $changedSchema->getKey(),
        ]);
        $this->assertDatabaseHas(MnemoSchemaNodeLink::class, [
            'node_id' => $nodeLink->node_id,
            'schema_id' => $originSchema->getKey(),
        ]);
    }

    public function test_change_node_link_from_null()
    {
        // Prepare
        $schema = MnemoSchema::factory()->create();
        $node = MnemoSchemaNode::factory()->create();

        $data = [
            'method_title' => CommandType::ChangeNodeLink->value,
            'data' => [
                'node_id' => $node->getKey(),
                'link' => [
                    'schema_name' => $schema->name,
                ],
            ]
        ];

        // Execute
        $response = $this->json('POST', $this->commandExecuteUrl, $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas(MnemoSchemaNodeLink::class, [
            'node_id' => $node->getKey(),
            'schema_id' => $schema->getKey(),
        ]);

        // Undo
        $response = $this->json('POST', $this->commandUndoUrl . $node->schema->name);
        $response->assertStatus(200);
        $this->assertDatabaseMissing(MnemoSchemaNodeLink::class, [
            'node_id' => $node->getKey(),
            'schema_id' => $schema->getKey(),
        ]);
    }

    public function test_change_node_hardware_and_parameter_codes(): void
    {
        // Prepare
        $origin = [
            'hardware_code' => rand(1, 100),
            'parameter_code' => rand(1, 100),
        ];
        $changed = [
            'hardware_code' => $origin['hardware_code'] + rand(1, 10),
            'parameter_code' => $origin['parameter_code'] + rand(1, 10),
        ];
        $nodeOptions = MnemoSchemaNodeOptions::factory()->create($origin);
        $data = [
            'method_title' => CommandType::ChangeNodeHardwareAndParameterCodes->value,
            'data' => [
                'node_id' => $nodeOptions->node_id,
                'parameter_code' => $changed['parameter_code'],
                'hardware_code' => $changed['hardware_code'],
            ]
        ];

        // Execute
        $response = $this->json('POST', $this->commandExecuteUrl, $data);
        $response->assertStatus(200);
        $this->assertDatabaseMissing(MnemoSchemaNodeOptions::class, [
            'node_id' => $nodeOptions->node_id,
            'parameter_code' => $origin['parameter_code'],
            'hardware_code' => $origin['hardware_code'],
        ]);
        $this->assertDatabaseHas(MnemoSchemaNodeOptions::class, [
            'node_id' => $nodeOptions->node_id,
            'parameter_code' => $changed['parameter_code'],
            'hardware_code' => $changed['hardware_code'],
        ]);

        // Undo
        $response = $this->json('POST', $this->commandUndoUrl . $nodeOptions->node->schema->name);
        $response->assertStatus(200);
        $this->assertDatabaseMissing(MnemoSchemaNodeOptions::class, [
            'node_id' => $nodeOptions->node_id,
            'parameter_code' => $changed['parameter_code'],
            'hardware_code' => $changed['hardware_code'],
        ]);
        $this->assertDatabaseHas(MnemoSchemaNodeOptions::class, [
            'node_id' => $nodeOptions->node_id,
            'parameter_code' => $origin['parameter_code'],
            'hardware_code' => $origin['hardware_code'],
        ]);
    }

    public function test_change_node_hardware_and_parameter_codes_to_null(): void
    {
        // Prepare
        $origin = [
            'hardware_code' => rand(1, 100),
            'parameter_code' => rand(1, 100),
        ];
        $changed = [
            'hardware_code' => null,
            'parameter_code' => null,
        ];
        $nodeOptions = MnemoSchemaNodeOptions::factory()->create($origin);
        $data = [
            'method_title' => CommandType::ChangeNodeHardwareAndParameterCodes->value,
            'data' => [
                'node_id' => $nodeOptions->node_id,
                'parameter_code' => $changed['parameter_code'],
                'hardware_code' => $changed['hardware_code'],
            ]
        ];

        // Execute
        $response = $this->json('POST', $this->commandExecuteUrl, $data);
        $response->assertStatus(200);
        $this->assertDatabaseMissing(MnemoSchemaNodeOptions::class, [
            'node_id' => $nodeOptions->node_id,
            'parameter_code' => $origin['parameter_code'],
            'hardware_code' => $origin['hardware_code'],
        ]);
        $this->assertDatabaseHas(MnemoSchemaNodeOptions::class, [
            'node_id' => $nodeOptions->node_id,
            'parameter_code' => $changed['parameter_code'],
            'hardware_code' => $changed['hardware_code'],
        ]);

        // Undo
        $response = $this->json('POST', $this->commandUndoUrl . $nodeOptions->node->schema->name);
        $response->assertStatus(200);
        $this->assertDatabaseMissing(MnemoSchemaNodeOptions::class, [
            'node_id' => $nodeOptions->node_id,
            'parameter_code' => $changed['parameter_code'],
            'hardware_code' => $changed['hardware_code'],
        ]);
        $this->assertDatabaseHas(MnemoSchemaNodeOptions::class, [
            'node_id' => $nodeOptions->node_id,
            'parameter_code' => $origin['parameter_code'],
            'hardware_code' => $origin['hardware_code'],
        ]);
    }

    public function test_change_node_hardware_code(): void
    {
        // Prepare
        $origin = [
            'hardware_code' => rand(1, 100),
            'parameter_code' => rand(1, 100),
        ];
        $changed = [
            'hardware_code' => $origin['hardware_code'] + rand(1, 10),
        ];
        $nodeOptions = MnemoSchemaNodeOptions::factory()->create($origin);
        $data = [
            'method_title' => CommandType::ChangeNodeHardwareAndParameterCodes->value,
            'data' => [
                'node_id' => $nodeOptions->node_id,
                'hardware_code' => $changed['hardware_code'],
            ]
        ];

        // Execute
        $response = $this->json('POST', $this->commandExecuteUrl, $data);
        $response->assertStatus(200);
        $this->assertDatabaseMissing(MnemoSchemaNodeOptions::class, [
            'node_id' => $nodeOptions->node_id,
            'parameter_code' => $origin['parameter_code'],
            'hardware_code' => $origin['hardware_code'],
        ]);
        $this->assertDatabaseHas(MnemoSchemaNodeOptions::class, [
            'node_id' => $nodeOptions->node_id,
            'parameter_code' => $origin['parameter_code'],
            'hardware_code' => $changed['hardware_code'],
        ]);

        // Undo
        $response = $this->json('POST', $this->commandUndoUrl . $nodeOptions->node->schema->name);
        $response->assertStatus(200);
        $this->assertDatabaseHas(MnemoSchemaNodeOptions::class, [
            'node_id' => $nodeOptions->node_id,
            'parameter_code' => $origin['parameter_code'],
            'hardware_code' => $origin['hardware_code'],
        ]);
        $this->assertDatabaseMissing(MnemoSchemaNodeOptions::class, [
            'node_id' => $nodeOptions->node_id,
            'parameter_code' => $origin['parameter_code'],
            'hardware_code' => $changed['hardware_code'],
        ]);
    }

    public function test_change_node_parameter_code(): void
    {
        // Prepare
        $origin = [
            'hardware_code' => rand(1, 100),
            'parameter_code' => rand(1, 100),
        ];
        $changed = [
            'parameter_code' => $origin['parameter_code'] + rand(1, 10),
        ];
        $nodeOptions = MnemoSchemaNodeOptions::factory()->create($origin);
        $data = [
            'method_title' => CommandType::ChangeNodeHardwareAndParameterCodes->value,
            'data' => [
                'node_id' => $nodeOptions->node_id,
                'parameter_code' => $changed['parameter_code'],
            ]
        ];

        // Execute
        $response = $this->json('POST', $this->commandExecuteUrl, $data);
        $response->assertStatus(200);
        $this->assertDatabaseMissing(MnemoSchemaNodeOptions::class, [
            'node_id' => $nodeOptions->node_id,
            'parameter_code' => $origin['parameter_code'],
            'hardware_code' => $origin['hardware_code'],
        ]);
        $this->assertDatabaseHas(MnemoSchemaNodeOptions::class, [
            'node_id' => $nodeOptions->node_id,
            'parameter_code' => $changed['parameter_code'],
            'hardware_code' => $origin['hardware_code'],
        ]);

        // Undo
        $response = $this->json('POST', $this->commandUndoUrl . $nodeOptions->node->schema->name);
        $response->assertStatus(200);
        $this->assertDatabaseHas(MnemoSchemaNodeOptions::class, [
            'node_id' => $nodeOptions->node_id,
            'parameter_code' => $origin['parameter_code'],
            'hardware_code' => $origin['hardware_code'],
        ]);
        $this->assertDatabaseMissing(MnemoSchemaNodeOptions::class, [
            'node_id' => $nodeOptions->node_id,
            'parameter_code' => $changed['parameter_code'],
            'hardware_code' => $origin['hardware_code'],
        ]);
    }

    public function test_change_node_size(): void
    {
        // Prepare
        $origin = [
            'width' => rand(1, 500),
            'height' => rand(1, 500),
        ];
        $changed = [
            'width' => rand(1, 500),
            'height' => rand(1, 500),
        ];
        $nodeAppearance = MnemoSchemaNodeAppearance::factory()->create($origin);
        $data = [
            'method_title' => CommandType::ChangeNodeSize->value,
            'data' => [
                'node_id' => $nodeAppearance->node_id,
                'height' => $changed['height'],
                'width' => $changed['width'],
            ]
        ];

        // Execute
        $response = $this->json('POST', $this->commandExecuteUrl, $data);
        $response->assertStatus(200);
        $this->assertDatabaseMissing(MnemoSchemaNodeAppearance::class, [
            'node_id' => $nodeAppearance->node_id,
            'height' => $origin['height'],
            'width' => $origin['width'],
        ]);
        $this->assertDatabaseHas(MnemoSchemaNodeAppearance::class, [
            'node_id' => $nodeAppearance->node_id,
            'height' => $changed['height'],
            'width' => $changed['width']
        ]);

        // Undo
        $response = $this->json('POST', $this->commandUndoUrl . $nodeAppearance->node->schema->name);
        $response->assertStatus(200);
        $this->assertDatabaseMissing(MnemoSchemaNodeAppearance::class, [
            'node_id' => $nodeAppearance->node_id,
            'height' => $changed['height'],
            'width' => $changed['width'],
        ]);
        $this->assertDatabaseHas(MnemoSchemaNodeAppearance::class, [
            'node_id' => $nodeAppearance->node_id,
            'height' => $origin['height'],
            'width' => $origin['width']
        ]);
    }

    public function test_change_node_size_width(): void
    {
        // Prepare
        $origin = [
            'width' => rand(1, 100),
            'height' => rand(1, 100),
        ];
        $changed = [
            'width' => $origin['width'] + rand(1, 10),
        ];
        $nodeAppearance = MnemoSchemaNodeAppearance::factory()->create($origin);
        $data = [
            'method_title' => CommandType::ChangeNodeSize->value,
            'data' => [
                'node_id' => $nodeAppearance->node_id,
                'width' => $changed['width'],
            ]
        ];

        // Execute
        $response = $this->json('POST', $this->commandExecuteUrl, $data);
        $response->assertStatus(200);
        $this->assertDatabaseMissing(MnemoSchemaNodeAppearance::class, [
            'node_id' => $nodeAppearance->node_id,
            'height' => $origin['height'],
            'width' => $origin['width'],
        ]);
        $this->assertDatabaseHas(MnemoSchemaNodeAppearance::class, [
            'node_id' => $nodeAppearance->node_id,
            'height' => $origin['height'],
            'width' => $changed['width']
        ]);

        // Undo
        $response = $this->json('POST', $this->commandUndoUrl . $nodeAppearance->node->schema->name);
        $response->assertStatus(200);
        $this->assertDatabaseMissing(MnemoSchemaNodeAppearance::class, [
            'node_id' => $nodeAppearance->node_id,
            'height' => $origin['height'],
            'width' => $changed['width'],
        ]);
        $this->assertDatabaseHas(MnemoSchemaNodeAppearance::class, [
            'node_id' => $nodeAppearance->node_id,
            'height' => $origin['height'],
            'width' => $origin['width']
        ]);
    }

    public function test_change_node_size_height(): void
    {
        // Prepare
        $origin = [
            'width' => rand(1, 100),
            'height' => rand(1, 100),
        ];
        $changed = [
            'height' => $origin['height'] + rand(1, 10),
        ];
        $nodeAppearance = MnemoSchemaNodeAppearance::factory()->create($origin);
        $data = [
            'method_title' => CommandType::ChangeNodeSize->value,
            'data' => [
                'node_id' => $nodeAppearance->node_id,
                'height' => $changed['height'],
            ]
        ];

        // Execute
        $response = $this->json('POST', $this->commandExecuteUrl, $data);
        $response->assertStatus(200);
        $this->assertDatabaseMissing(MnemoSchemaNodeAppearance::class, [
            'node_id' => $nodeAppearance->node_id,
            'height' => $origin['height'],
            'width' => $origin['width'],
        ]);
        $this->assertDatabaseHas(MnemoSchemaNodeAppearance::class, [
            'node_id' => $nodeAppearance->node_id,
            'height' => $changed['height'],
            'width' => $origin['width']
        ]);

        // Undo
        $response = $this->json('POST', $this->commandUndoUrl . $nodeAppearance->node->schema->name);
        $response->assertStatus(200);
        $this->assertDatabaseMissing(MnemoSchemaNodeAppearance::class, [
            'node_id' => $nodeAppearance->node_id,
            'height' => $changed['height'],
            'width' => $origin['width'],
        ]);
        $this->assertDatabaseHas(MnemoSchemaNodeAppearance::class, [
            'node_id' => $nodeAppearance->node_id,
            'height' => $origin['height'],
            'width' => $origin['width']
        ]);
    }

    public function test_change_node_title(): void
    {
        // Prepare
        $originTitle = 'origin title';
        $newTitle = 'changed title';
        $node = MnemoSchemaNode::factory()
            ->create(['title' => $originTitle]);
        $data = [
            'method_title' => CommandType::ChangeNodeTitle->value,
            'data' => [
                'node_id' => $node->getKey(),
                'node_title' => $newTitle
            ]
        ];

        // Execute
        $response = $this->json('POST', $this->commandExecuteUrl, $data);
        $updatedNode = MnemoSchemaNode::query()->find($node->getKey());
        $response->assertStatus(200);
        $this->assertEquals($newTitle, $updatedNode->title);


        // Undo
        $response = $this->json('POST', $this->commandUndoUrl . $node->schema->name);
        $originNode = MnemoSchemaNode::query()->find($node->getKey());
        $response->assertStatus(200);
        $this->assertEquals($originTitle, $originNode->title);
    }

    public function test_change_node_type()
    {
        // Prepare
        $originType = MnemoSchemaNodeType::factory()->create();
        $changedType = MnemoSchemaNodeType::factory()->create();
        $node = MnemoSchemaNode::factory()
            ->create(['type_id' => $originType->getKey()]);
        $data = [
            'method_title' => CommandType::ChangeNodeType->value,
            'data' => [
                'node_id' => $node->getKey(),
                'node_type' => $changedType->type
            ]
        ];

        // Execute
        $response = $this->json('POST', $this->commandExecuteUrl, $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas(MnemoSchemaNode::class, [
            'id' => $node->getKey(),
            'type_id' => $changedType->getKey(),
        ]);
        $this->assertDatabaseMissing(MnemoSchemaNode::class, [
            'id' => $node->getKey(),
            'type_id' => $originType->getKey(),
        ]);

        // Undo
        $response = $this->json('POST', $this->commandUndoUrl . $node->schema->name);
        $response->assertStatus(200);
        $this->assertDatabaseHas(MnemoSchemaNode::class, [
            'id' => $node->getKey(),
            'type_id' => $originType->getKey(),
        ]);
        $this->assertDatabaseMissing(MnemoSchemaNode::class, [
            'id' => $node->getKey(),
            'type_id' => $changedType->getKey(),
        ]);
    }

    public function test_delete_command_from_node(): void
    {
        $cat = new CatalogSignalService();
        /** @var Signal $signal */
        $signal = $cat->getAllReadableSignals()?->first();

        // Prepare
        $node = MnemoSchemaNode::factory()
            ->has(
                MnemoSchemaNodeOptions::factory()->state(['hardware_code' => $signal->transportHardwareId]),
                'options'
            )
            ->has(
                MnemoSchemaNodeCommand::factory()->state(['parameter_code' => $signal->transportSignalId]),
                'commands'
            )
            ->create();
        $parameterCode = $node->commands->first()->parameter_code;

        // Execute
        $data = [
            'method_title' => CommandType::DeleteCommandFromNode->value,
            'data' => [
                'node_id' => $node->getKey(),
                'parameter_code' => $parameterCode
            ]
        ];
        $response = $this->json('POST', $this->commandExecuteUrl, $data);
        $response->assertStatus(200);
        $this->assertDatabaseMissing(MnemoSchemaNodeCommand::class, [
            'node_id' => $node->getKey(),
            'parameter_code' => $parameterCode
        ]);

        // Undo
        $response = $this->json('POST', $this->commandUndoUrl . $node->schema->name);
        $response->assertStatus(200);
        $this->assertDatabaseHas(MnemoSchemaNodeCommand::class, [
            'node_id' => $node->getKey(),
            'parameter_code' => $parameterCode
        ]);
    }

    public function test_delete_node_from_schema(): void
    {
        // Prepare
        $nodeTitle = Str::random();
        $node = MnemoSchemaNode::factory()
            ->has(MnemoSchemaNodeOptions::factory(), 'options')
            ->has(MnemoSchemaNodeAppearance::factory(), 'appearance')
            ->has(MnemoSchemaNodeGeometry::factory(), 'geometry')
            ->has(
                MnemoSchemaLine::factory()
                    ->has(MnemoSchemaLineOptions::factory(), 'options')
                    ->has(MnemoSchemaLineAppearance::factory(), 'appearance'),
                'from_lines'
            )
            ->has(
                MnemoSchemaLine::factory()
                    ->has(MnemoSchemaLineOptions::factory(), 'options')
                    ->has(MnemoSchemaLineAppearance::factory(), 'appearance'),
                'to_lines'
            )
            ->create(['title' => $nodeTitle]);
        $data = [
            'method_title' => CommandType::DeleteNodeFromSchema->value,
            'data' => [
                'node_id' => $node->id
            ]
        ];

        // Execute
        $response = $this->json('POST', $this->commandExecuteUrl, $data);
        $response->assertStatus(200);
        $this->assertDatabaseMissing(MnemoSchemaNode::class, ['id' => $node->getKey()]);
        $this->assertDatabaseMissing(MnemoSchemaNodeOptions::class, ['node_id' => $node->getKey()]);
        $this->assertDatabaseMissing(MnemoSchemaNodeAppearance::class, ['node_id' => $node->getKey()]);
        $this->assertDatabaseMissing(MnemoSchemaNodeGeometry::class, ['node_id' => $node->getKey()]);
        $this->assertDatabaseMissing(MnemoSchemaLine::class, ['first_node' => $node->getKey()]);
        $this->assertDatabaseMissing(MnemoSchemaLine::class, ['second_node' => $node->getKey()]);

        // Undo
        $response = $this->json('POST', $this->commandUndoUrl . $node->schema->name);
        $response->assertStatus(200);
        $node = MnemoSchemaNode::query()->where(['title' => $nodeTitle])->first();
        $this->assertInstanceOf(MnemoSchemaNode::class, $node);
        $this->assertDatabaseHas(MnemoSchemaNodeOptions::class, ['node_id' => $node->getKey()]);
        $this->assertDatabaseHas(MnemoSchemaNodeAppearance::class, ['node_id' => $node->getKey()]);
        $this->assertDatabaseHas(MnemoSchemaNodeGeometry::class, ['node_id' => $node->getKey()]);
        $this->assertDatabaseHas(MnemoSchemaLine::class, ['first_node' => $node->getKey()]);
    }

    public function test_delete_node_label(): void
    {
        // Prepare
        $nodeOptions = MnemoSchemaNodeOptions::factory()->create(['label' => 'label']);
        $data = [
            'method_title' => CommandType::DeleteNodeLabel->value,
            'data' => [
                'node_id' => $nodeOptions->node_id
            ]
        ];

        // Execute
        $response = $this->json('POST', $this->commandExecuteUrl, $data);
        $response->assertStatus(200);
        $this->assertDatabaseMissing(MnemoSchemaNodeOptions::class, [
            'node_id' => $nodeOptions->node_id,
            'label' => $nodeOptions->label
        ]);

        // Undo
        $response = $this->json('POST', $this->commandUndoUrl . $nodeOptions->node->schema->name);
        $response->assertStatus(200);
        $this->assertDatabaseHas(MnemoSchemaNodeOptions::class, [
            'node_id' => $nodeOptions->node_id,
            'label' => $nodeOptions->label
        ]);
    }

    public function test_manipulate_node_commands()
    {
        $originCommands = [1, 2, 3];
        $changedCommands = [2, 3, 5];

        // Prepare
        $node = MnemoSchemaNode::factory()
            ->has(
                MnemoSchemaNodeCommand::factory()
                    ->count(count($originCommands))
                    ->state(
                        new Sequence(
                            fn(Sequence $sequence) => ['parameter_code' => $originCommands[$sequence->index]],
                        )
                    ),
                'commands'
            )
            ->create();

        $data = [
            'method_title' => CommandType::ManipulateNodeCommands->value,
            'data' => [
                'node_id' => $node->getKey(),
                'commands' => $changedCommands
            ]
        ];


        // Execute
        $response = $this->json('POST', $this->commandExecuteUrl, $data);
        $response->assertStatus(200);

        // Rest
        $restParameterCodes = array_diff($originCommands, $changedCommands);
        foreach ($restParameterCodes as $parameterCode) {
            $this->assertDatabaseMissing(MnemoSchemaNodeCommand::class, [
                'node_id' => $node->getKey(),
                'parameter_code' => $parameterCode
            ]);
        }

        // Common
        $commandAndNewParameterCodes = array_merge(
            array_intersect($originCommands, $changedCommands),
            array_diff($changedCommands, $originCommands)
        );
        foreach ($commandAndNewParameterCodes as $parameterCode) {
            $this->assertDatabaseHas(MnemoSchemaNodeCommand::class, [
                'node_id' => $node->getKey(),
                'parameter_code' => $parameterCode
            ]);
        }

        // Undo
        $response = $this->json('POST', $this->commandUndoUrl . $node->schema->name);
        $response->assertStatus(200);

        foreach ($originCommands as $parameterCode) {
            $this->assertDatabaseHas(MnemoSchemaNodeCommand::class, [
                'node_id' => $node->getKey(),
                'parameter_code' => $parameterCode
            ]);
        }
    }
}
