<?php

namespace Tests\Feature\Commands;

use App\Enums\CommandType;
use App\Models\MnemoSchema;
use App\Models\MnemoSchemaLine;
use App\Models\MnemoSchemaLineAppearance;
use App\Models\MnemoSchemaLineArrowType;
use App\Models\MnemoSchemaLineOptions;
use App\Models\MnemoSchemaLineType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

/**
 * Line Commands Test
 */
class LineCommandsTest extends BaseCommandTest
{
    use RefreshDatabase;

    public function test_add_line(): void
    {
        // Prepare
        $schema = MnemoSchema::factory()->create();
        $options = [
            'label' => Str::random(),
        ];
        $appearance = [
            'color' => '#FFFFFF',
            'opacity' => 100,
            'width' => 2
        ];

        $data = [
            'method_title' => CommandType::AddLine->value,
            'data' => [
                'schema_name' => $schema->name,
                'first_node' => 1,
                'second_node' => 2,
                'source_position' => rand(1, 4),
                'target_position' => rand(1, 4),
                'options' => [
                    'label' => $options['label'],
                    'appearance' => $appearance
                ]
            ]
        ];

        // Execute
        $response = $this->json('POST', $this->commandExecuteUrl, $data);
        $response->assertStatus(200);
        $line = MnemoSchemaLineOptions::query()
            ->where(['text' => $options['label']])
            ->first()
            ?->line;
        $this->assertInstanceOf(MnemoSchemaLine::class, $line);
        $this->assertDatabaseHas(MnemoSchemaLineOptions::class, ['line_id' => $line->getKey()]);
        $this->assertDatabaseHas(MnemoSchemaLineAppearance::class, ['line_id' => $line->getKey()]);

        // Undo
        $response = $this->json('POST', $this->commandUndoUrl . $schema->name);
        $response->assertStatus(200);
        $this->assertDatabaseMissing(MnemoSchemaLine::class, ['id' => $line->getKey()]);
        $this->assertDatabaseMissing(MnemoSchemaLineOptions::class, ['line_id' => $line->getKey()]);
        $this->assertDatabaseMissing(MnemoSchemaLineAppearance::class, ['line_id' => $line->getKey()]);
    }

    public function test_delete_line(): void
    {
        // Prepare
        $lineOptionsText = Str::random();
        $line = MnemoSchemaLine::factory()
            ->has(MnemoSchemaLineOptions::factory(['text' => $lineOptionsText]), 'options')
            ->has(MnemoSchemaLineAppearance::factory(), 'appearance')
            ->create();

        $data = [
            'method_title' => CommandType::DeleteLine->value,
            'data' => [
                'line_id' => $line->getKey()
            ]
        ];

        // Execute
        $response = $this->json('POST', $this->commandExecuteUrl, $data);
        $response->assertStatus(200);
        $this->assertDatabaseMissing(MnemoSchemaLine::class, [
            'id' => $line->getKey()
        ]);
        $this->assertDatabaseMissing(MnemoSchemaLineOptions::class, [
            'line_id' => $line->getKey(),
        ]);
        $this->assertDatabaseMissing(MnemoSchemaLineAppearance::class, [
            'line_id' => $line->getKey()
        ]);

        // Undo
        $response = $this->json('POST', $this->commandUndoUrl . $line->schema->name);
        $response->assertStatus(200);

        $line = MnemoSchemaLineOptions::query()->where(['text' => $lineOptionsText])->first()?->line;
        $this->assertInstanceOf(MnemoSchemaLine::class, $line);
        $this->assertDatabaseHas(MnemoSchemaLineOptions::class, [
            'line_id' => $line->getKey(),
        ]);
        $this->assertDatabaseHas(MnemoSchemaLineAppearance::class, [
            'line_id' => $line->getKey()
        ]);
    }

    public function test_change_line_direction()
    {
        // Prepare
        $line = MnemoSchemaLine::factory()
            ->has(
                MnemoSchemaLineOptions::factory([
                    'first_arrow' => null,
                    'second_arrow' => null,
                ]),
                'options'
            )
            ->create();
        $arrowTypeTitle = Str::random();
        $arrowType = MnemoSchemaLineArrowType::factory()->create(['arrow_type_title' => $arrowTypeTitle]);
        $data = [
            'method_title' => CommandType::ChangeLineDirection->value,
            'data' => [
                'line_id' => $line->getKey(),
                'first_arrow' => null,
                'second_arrow' => $arrowType->arrow_type_title
            ]
        ];

        // Execute
        $response = $this->json('POST', $this->commandExecuteUrl, $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas(MnemoSchemaLineOptions::class, [
            'line_id' => $line->getKey(),
            'second_arrow' => $arrowType->getKey()
        ]);

        // Undo
        $response = $this->json('POST', $this->commandUndoUrl . $line->schema->name);
        $response->assertStatus(200);
        $this->assertDatabaseMissing(MnemoSchemaLineOptions::class, [
            'line_id' => $line->getKey(),
            'second_arrow' => $arrowType->getKey()
        ]);
    }

    public function test_change_line_direction_set_null()
    {
        // Prepare
        $arrowTypeTitle = Str::random();
        $arrowType = MnemoSchemaLineArrowType::factory()->create(['arrow_type_title' => $arrowTypeTitle]);
        $line = MnemoSchemaLine::factory()
            ->has(
                MnemoSchemaLineOptions::factory([
                    'first_arrow' => $arrowType->getKey(),
                    'second_arrow' => $arrowType->getKey(),
                ]),
                'options'
            )
            ->create();
        $data = [
            'method_title' => CommandType::ChangeLineDirection->value,
            'data' => [
                'line_id' => $line->getKey(),
                'first_arrow' => null,
                'second_arrow' => null
            ]
        ];

        // Execute
        $response = $this->json('POST', $this->commandExecuteUrl, $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas(MnemoSchemaLineOptions::class, [
            'line_id' => $line->getKey(),
            'first_arrow' => null,
            'second_arrow' => null,
        ]);

        // Undo
        $response = $this->json('POST', $this->commandUndoUrl . $line->schema->name);
        $response->assertStatus(200);
        $this->assertDatabaseHas(MnemoSchemaLineOptions::class, [
            'line_id' => $line->getKey(),
            'first_arrow' => $arrowType->getKey(),
            'second_arrow' => $arrowType->getKey(),
        ]);
    }

    public function test_change_line_appearance()
    {
        // Prepare
        $originAppearance = [
            'color' => '#FFFFFF',
            'opacity' => 100,
            'width' => 1
        ];
        $changedAppearance = [
            'color' => '#FFFFFF',
            'opacity' => 50,
            'width' => 3
        ];
        $line = MnemoSchemaLine::factory()
            ->has(MnemoSchemaLineAppearance::factory($originAppearance), 'appearance')
            ->create();
        $data = [
            'method_title' => CommandType::ChangeLineAppearances->value,
            'data' => ['line_id' => $line->getKey()] + $changedAppearance
        ];

        // Execute
        $response = $this->json('POST', $this->commandExecuteUrl, $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas(
            MnemoSchemaLineAppearance::class,
            ['line_id' => $line->getKey()] + $changedAppearance
        );

        // Undo
        $response = $this->json('POST', $this->commandUndoUrl . $line->schema->name);
        $response->assertStatus(200);
        $this->assertDatabaseHas(
            MnemoSchemaLineAppearance::class,
            ['line_id' => $line->getKey()] + $originAppearance
        );
    }

    public function test_change_line_options()
    {
        $lineType = MnemoSchemaLineType::factory()->create();
        $arrowType = MnemoSchemaLineArrowType::factory()->create();

        $originOptions = [
            'label' => 'origin label',
            'first_arrow' => $arrowType->getKey(),
            'second_arrow' => $arrowType->getKey(),
        ];
        $changedOptions = [
            'label' => 'changed label',
            'first_arrow' => null,
            'second_arrow' => null,
        ];
        // Prepare
        $line = MnemoSchemaLine::factory()
            ->has(
                MnemoSchemaLineOptions::factory([
                    'text' => $originOptions['label'],
                    'type_id' => $lineType->getKey(),
                    'first_arrow' => $arrowType->getKey(),
                    'second_arrow' => $arrowType->getKey(),
                ]),
                'options'
            )
            ->create();
        $data = [
            'method_title' => CommandType::ChangeLineOptions->value,
            'data' => [
                'line_id' => $line->getKey(),
                'label' => $changedOptions['label'],
                'type' => $lineType->type,
                'first_arrow' => $changedOptions['first_arrow'],
                'second_arrow' => $changedOptions['second_arrow'],
            ]
        ];

        // Execute
        $response = $this->json('POST', $this->commandExecuteUrl, $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas(
            MnemoSchemaLineOptions::class,
            [
                'line_id' => $line->getKey(),
                'text' => $changedOptions['label'],
                'type_id' => $lineType->getKey(),
                'first_arrow' => $changedOptions['first_arrow'],
                'second_arrow' => $changedOptions['second_arrow'],
            ]
        );

        // Undo
        $response = $this->json('POST', $this->commandUndoUrl . $line->schema->name);
        $response->assertStatus(200);
        $this->assertDatabaseHas(
            MnemoSchemaLineOptions::class,
            [
                'line_id' => $line->getKey(),
                'text' => $originOptions['label'],
                'type_id' => $lineType->getKey(),
                'first_arrow' => $originOptions['first_arrow'],
                'second_arrow' => $originOptions['second_arrow'],
            ]
        );
    }
}
