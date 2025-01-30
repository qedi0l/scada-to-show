<?php

namespace Tests\Unit\Services;

use App\Contracts\IScadaUILine;
use App\Contracts\IScadaUINode;
use App\Models\MnemoSchema;
use App\Models\MnemoSchemaLine;
use App\Models\MnemoSchemaLineAppearance;
use App\Models\MnemoSchemaLineOptions;
use App\Models\MnemoSchemaLineType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScadaUILineServiceTest extends TestCase
{

    use RefreshDatabase;

    protected IScadaUILine $scadaUILineService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->scadaUILineService = $this->app->make(IScadaUILine::class);
    }


    public function test_get_lines_by_schema()
    {
        $schema = MnemoSchema::factory()->create();

        $line = MnemoSchemaLine::factory()->create(['schema_id' => $schema->id]);

        $appearance = MnemoSchemaLineAppearance::factory()->create(['line_id' => $line->id]);

        $options = MnemoSchemaLineOptions::factory()->create(['line_id' => $line->id]);

        $lineType = MnemoSchemaLineType::factory()->create();

        $options->type_id = $lineType->id;
        $options->save();

        $linesData = $this->scadaUILineService->getLinesBySchema($schema);

        $this->assertIsArray($linesData);

        $this->assertCount(1, $linesData);

        $this->assertEquals($line->id, $linesData[0]['id']);
        $this->assertEquals($line->first_node, $linesData[0]['first_node']);
        $this->assertEquals($line->second_node, $linesData[0]['second_node']);
        $this->assertEquals($options->text, $linesData[0]['options']['label']);
        $this->assertEquals($lineType->type, $linesData[0]['options']['type']);
        $this->assertEquals($appearance->color, $linesData[0]['options']['appearance']['color']);
        $this->assertEquals($appearance->opacity, $linesData[0]['options']['appearance']['opacity']);
        $this->assertEquals($appearance->width, $linesData[0]['options']['appearance']['width']);
    }


}
