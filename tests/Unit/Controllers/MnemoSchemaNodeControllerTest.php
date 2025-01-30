<?php

namespace Tests\Unit\Controllers;

use App\Models\MnemoSchema;
use App\Models\MnemoSchemaNode;
use App\Models\MnemoSchemaNodeOptions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MnemoSchemaNodeControllerTest extends TestCase
{
    use RefreshDatabase;

    public string $urlHost = '/api/scada-ui/api/v1/scada/ui';

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_get_child_nodes_route()
    {
        $schema = MnemoSchema::factory()->create();
        $parentNode = MnemoSchemaNode::factory()->create();
        $childNode = MnemoSchemaNode::factory()->create();

        MnemoSchemaNodeOptions::factory()->create([
            'node_id' => $childNode->id,
            'parent_id' => $parentNode->id
        ]);

        $response = $this->get("$this->urlHost/get/child/nodes/$childNode->id");

        $response->assertStatus(200);
    }



}
