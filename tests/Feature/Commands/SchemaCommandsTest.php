<?php

namespace Tests\Feature\Commands;

use App\Enums\CommandType;
use App\Models\MnemoSchema;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

/**
 * Schema Commands Test
 */
class SchemaCommandsTest extends BaseCommandTest
{
    use RefreshDatabase;

    public function test_add_schema(): void
    {
        // Prepare
        $name = Str::random();
        $title = Str::random();
        $data = [
            'method_title' => CommandType::AddSchema->value,
            'data' => [
                'schema_name' => $name,
                'schema_title' => $title
            ]
        ];

        // Execute
        $response = $this->json('POST', $this->commandExecuteUrl, $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas(MnemoSchema::class, [
            'name' => $name,
            'title' => $title
        ]);
    }

    public function test_add_schema_without_name(): void
    {
        // Prepare
        $title = Str::random();
        $data = [
            'method_title' => CommandType::AddSchema->value,
            'data' => [
                'schema_title' => $title
            ]
        ];

        // Execute
        $response = $this->json('POST', $this->commandExecuteUrl, $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas(MnemoSchema::class, [
            'title' => $title
        ]);
    }

    public function test_change_schema_title()
    {
        // Prepare
        $schema = MnemoSchema::factory()->create();
        $originTitle = $schema->title;
        $changedTitle = Str::random();
        $data = [
            'method_title' => CommandType::ChangeSchemaTitle->value,
            'data' => [
                'schema_name' => $schema->name,
                'schema_title' => $changedTitle
            ]
        ];

        // Execute
        $response = $this->json('POST', $this->commandExecuteUrl, $data);
        $response->assertStatus(200);
        $this->assertDatabaseMissing(MnemoSchema::class, [
            'name' => $schema->name,
            'title' => $originTitle
        ]);
        $this->assertDatabaseHas(MnemoSchema::class, [
            'name' => $schema->name,
            'title' => $changedTitle
        ]);
    }

    public function test_delete_schema()
    {
        // Prepare
        $schema = MnemoSchema::factory()->create();
        $data = [
            'method_title' => CommandType::DeleteSchema->value,
            'data' => [
                'schema_name' => $schema->name,
            ]
        ];

        // Execute
        $response = $this->json('POST', $this->commandExecuteUrl, $data);
        $response->assertStatus(200);
        $this->assertDatabaseMissing(MnemoSchema::class, [
            'name' => $schema->name,
        ]);
    }

    public function test_make_schema_default()
    {
        // Prepare
        $schemas = MnemoSchema::factory()->count(10)->create();
        /** @var MnemoSchema $schema */
        $schema = $schemas->random();
        $data = [
            'method_title' => CommandType::MakeSchemaDefault->value,
            'data' => [
                'schema_name' => $schema->name,
            ]
        ];

        // Execute
        $response = $this->json('POST', $this->commandExecuteUrl, $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas(MnemoSchema::class, [
            'default' => true,
            'name' => $schema->name,
        ]);
    }
}
