<?php

namespace Tests\Feature\Controllers;

use App\Models\MnemoSchema;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class SchemaPreviewControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_schema_preview(): void
    {
        Storage::fake('schema_previews');

        $file = UploadedFile::fake()->image('image.jpg');
        $schema = MnemoSchema::factory()->create();

        // Store Tests
        $url = env('APP_PREFIX') . '/v1/scada/ui/' . 'schema/' . $schema->name . '/preview';

        $response = $this->post($url, ['preview' => $file]);

        $fileName = Str::afterLast($response->json('preview'), '/');

        $schema = MnemoSchema::query()->where('name', $schema->name)->firstOrFail();

        // Check Status Code
        $response->assertStatus(200);

        // Check Response Structure
        $response->assertJsonStructure(['preview']);

        // Check DB fill field
        $this->assertIsString($schema->preview_file_name);

        // Check File Existing
        Storage::disk('schema_previews')->assertExists($fileName);



        // Destroy Tests
        $response = $this->delete($url);

        $schema = MnemoSchema::query()->where('name', $schema->name)->firstOrFail();


        // Check Status Code
        $response->assertStatus(200);

        // Check Response Structure
        $response->assertJsonStructure(['preview']);

        // Test Unset DB Field
        $this->assertEmpty($schema->preview_file_name);

        // Check File Dropping
        Storage::disk('schema_previews')->assertMissing($fileName);
    }
}
