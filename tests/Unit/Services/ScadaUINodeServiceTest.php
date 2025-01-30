<?php

namespace Tests\Unit\Services;

use App\Contracts\IScadaUINode;
use App\Models\MnemoSchema;
use App\Models\MnemoSchemaNode;
use App\Models\MnemoSchemaNodeOptions;
use App\Models\MnemoSchemaNodeType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class ScadaUINodeServiceTest extends TestCase
{
    use RefreshDatabase;
    protected IScadaUINode $scadaUINodeService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->scadaUINodeService = $this->app->make(IScadaUINode::class);
    }

    public function test_show_hierarchy_by_mnemo_schema()
    {
        $schema = MnemoSchema::factory()->create();
        $parentNodeId = 0;

        $response = $this->scadaUINodeService->showHierarchyByMnemoSchema($schema->name, $parentNodeId);

        $this->assertInstanceOf(JsonResponse::class,$response);
        $this->assertArrayHasKey('nodes_info', $response->getData(true));
    }

    public function test_get_service_nodes_by_schema()
    {
        $schema = MnemoSchema::factory()->create();

        $serviceNodeType = MnemoSchemaNodeType::factory()->create([
            'service_type' => true
        ]);

        $regularNodeType = MnemoSchemaNodeType::factory()->create([
            'service_type' => false
        ]);

        $serviceNode = MnemoSchemaNode::factory()->create([
            'schema_id' => $schema->id,
            'type_id' => $serviceNodeType->id
        ]);

        $regularNode = MnemoSchemaNode::factory()->create([
            'schema_id' => $schema->id,
            'type_id' => $regularNodeType->id
        ]);

        MnemoSchemaNodeOptions::factory()->create([
            'node_id' => $serviceNode->id,
        ]);

        $serviceNodes = $this->scadaUINodeService->getServiceNodesBySchema($schema);

        $this->assertIsArray($serviceNodes);
        $this->assertCount(1, $serviceNodes);
        $this->assertEquals($serviceNode->id, $serviceNodes[0]['id']);
    }





    public function test_get_child_nodes()
    {
        $node = MnemoSchemaNode::factory()->create();
        $schema = MnemoSchema::factory()->create();

        $childNodes = MnemoSchemaNode::factory()
            ->count(3)
            ->create([
                'schema_id' => $node->schema_id
            ]);

        foreach ($childNodes as $childNode) {
            MnemoSchemaNodeOptions::factory()->create([
                'node_id' => $childNode->id,
                'parent_id' => $node->id
            ]);
        }

        $result = $this->scadaUINodeService->getChildNodes($node->id);

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(3, $result);

    }


}
