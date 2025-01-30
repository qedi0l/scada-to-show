<?php

namespace Tests\Unit\Services;

use App\Contracts\IScadaUI;
use App\Models\MnemoSchema;
use App\Models\MnemoSchemaNode;
use App\Models\MnemoSchemaNodeOptions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScadaUIServiceTest extends TestCase
{
    use RefreshDatabase;
    protected IScadaUI $scadaUIService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->scadaUIService = $this->app->make(IScadaUI::class);
    }

    public function test_get_data_by_schema_id()
    {
        $schema = MnemoSchema::factory()->create();

        $result = $this->scadaUIService->getDataBySchemaId($schema);

        $this->assertIsArray($result);

        $this->assertArrayHasKey('Schema', $result);

        $this->assertEquals($schema->id, $result['Schema']['id']);
        $this->assertEquals($schema->title, $result['Schema']['title']);
        $this->assertEquals($schema->name, $result['Schema']['name']);
    }

    public function test_get_node_params_by_schema_name()
    {
        $schema = MnemoSchema::factory()->create(['name' => 'test']);

        $nodes = MnemoSchemaNode::factory()
            ->count(3)
            ->create([
                'schema_id' => $schema->id
            ]);

        foreach ($nodes as $node) {
            $nodeOptions = MnemoSchemaNodeOptions::factory()->create([
                'node_id' => $node->id
            ]);

            $node->generatedOptions = $nodeOptions;

        }

        $result = $this->scadaUIService->getNodeParamsBySchemaName('test');

        $this->assertCount(3, $result->nodes);

        $firstNode = $result->nodes->first();
        $firstGeneratedNode = $nodes->first();

        $this->assertEquals($firstGeneratedNode->generatedOptions->parameter_code, $firstNode->options[0]['parameter_code']);
        $this->assertEquals($firstGeneratedNode->generatedOptions->hardware_code, $firstNode->options[0]['hardware_code']);
    }

    public function test_get_all_mnemo_schemas()
    {
        $schemas = MnemoSchema::factory()->count(3)->create();

        $result = $this->scadaUIService->getAllMnemoSchemas();

        $this->assertIsArray($result);

        $this->assertCount(3, $result);

        $firstSchema = $result[0];
        $this->assertArrayHasKey('id', $firstSchema);
        $this->assertArrayHasKey('title', $firstSchema);
        $this->assertArrayHasKey('name', $firstSchema);
    }

    public function test_get_signals_of_single_schema()
    {
        $schema = MnemoSchema::factory()->create(['name' => 'test']);

        $nodes = MnemoSchemaNode::factory()
            ->count(2)
            ->create([
                'schema_id' => $schema->id
            ]);

        foreach ($nodes as $node) {
            MnemoSchemaNodeOptions::factory()->create([
                'node_id' => $node->id
            ]);
        }

        $result = $this->scadaUIService->getSignalsOfSingleSchema('test');

        $this->assertIsArray($result);

        $this->assertEquals('test', $result['schema_name']);
        $this->assertArrayHasKey('signals', $result);

        $nodeOptions = MnemoSchemaNodeOptions::first();

        $this->assertArrayHasKey('hardware_code', $result['signals'][$nodeOptions->hardware_code]);
        $this->assertEquals([$nodeOptions->parameter_code], $result['signals'][$nodeOptions->hardware_code]['parameter_code']);
    }

    public function test_get_schema_titles()
    {
        $schemas = MnemoSchema::factory()->createMany([
            ['title' => 'Schema 1', 'name' => 'schema_1'],
            ['title' => 'Schema 2', 'name' => 'schema_2'],
            ['title' => 'Schema 3', 'name' => 'schema_3'],
        ]);

        $result = $this->scadaUIService->getSchemaTitles();

        $this->assertIsArray($result);

        $this->assertCount(3, $result);

        foreach ($schemas as $index => $schema) {
            $this->assertEquals($schema->title, $result[$index]['title']);
            $this->assertEquals($schema->name, $result[$index]['name']);
        }
    }

}
