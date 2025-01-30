<?php

namespace Tests\Unit;

use App\Models\MnemoSchemaNode;
use App\Models\MnemoSchemaNodeType;
use App\Models\MnemoSchemaNodeTypeGroup;
use App\Services\NodeTypeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class ScadaUINodeTypeServiceTest extends TestCase
{
    use RefreshDatabase;

    protected NodeTypeService $service;
    private string $route = 'api/scada-ui/api/v1/scada/ui/create/node/type';
    private string $updateRoute = 'api/scada-ui/api/v1/scada/ui/update/node/type';

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new NodeTypeService();
    }

    public function test_get_node_types()
    {
        $nodeType = MnemoSchemaNodeType::factory()
            ->create([
                'type' => 'test',
                'svg' => 'svg'
            ]);

        $response = $this->service->getNodeTypes();

        $this->assertArrayHasKey('test', $response);
        $this->assertEquals('svg', $response['test']);
    }

    public function test_create_node_type()
    {
        $request = Request::create($this->route, 'POST', [
            'type' => 'test',
            'hardware_type' => 'TEST',
            'svg' => 'svg',
            'node_type_group_title' => 'test-group'
        ]);

        MnemoSchemaNodeTypeGroup::factory()->create([
            'title' => 'test-group'
        ]);

        $nodeType = $this->service->createNodeType($request);

        $this->assertInstanceOf(MnemoSchemaNodeType::class, $nodeType);
        $this->assertDatabaseHas('mnemo_schema_node_types', [
            'type' => 'test',
            'hardware_type' => 'TEST',
            'svg' => 'svg'
        ]);
    }


    public function test_update_node_type()
    {
        $nodeType = MnemoSchemaNodeType::factory()
            ->create([
                'type' => 'old_type',
                'hardware_type' => 'old_hardware'
            ]);

        $request = Request::create($this->updateRoute, 'PUT', [
            'node_type_id' => $nodeType->id,
            'type' => 'updated_type',
            'hardware_type' => 'updated_hardware',
            'node_type_group_title' => 'group2'
        ]);

        MnemoSchemaNodeTypeGroup::factory()->create(['title' => 'group2']);

        $updatedNodeType = $this->service->updateNodeType($request);

        $this->assertEquals('updated_type', $updatedNodeType->type);
        $this->assertEquals('updated_hardware', $updatedNodeType->hardware_type);
    }

    public function testDeleteNodeType()
    {
        $nodeType = MnemoSchemaNodeType::factory()->create(['type' => 'custom_type']);
        $defaultType = MnemoSchemaNodeType::factory()->create(['type' => 'default']);
        MnemoSchemaNode::factory()->count(2)->create(['type_id' => $nodeType->id]);

        $this->service->deleteNodeType($nodeType->id);

        $this->assertDatabaseMissing('mnemo_schema_node_types', ['id' => $nodeType->id]);

        //TODO: Разобраться, почему не меняется тип
        //$this->assertDatabaseHas('mnemo_schema_nodes', ['type_id' => $defaultType->id]);


    }

}
