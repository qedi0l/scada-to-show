<?php

namespace Tests\Feature\Services;

use App\Services\SchemaPreviewService;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SchemaPreviewServiceTest extends TestCase
{
    protected SchemaPreviewService $service;

    public function __construct(string $name)
    {
        $this->service = new SchemaPreviewService();

        parent::__construct($name);
    }

    /**
     * A basic feature test example.
     */
    public function test_save_file(): void
    {
        Storage::fake('schema_previews');

        $file = UploadedFile::fake()->image('image.jpg');

        $fileName = $this->service->saveFile($file);

        Storage::disk('schema_previews')->assertExists($fileName);
    }

    /**
     * @throws Exception
     */
    public function test_delete_file()
    {
        Storage::fake('schema_previews');

        $file = UploadedFile::fake()->image('image.jpg');

        $fileName = Storage::disk('schema_previews')->putFileAs('', $file, 'image.jpg');

        $this->service->deleteFile($fileName);

        Storage::disk('schema_previews')->assertMissing($fileName);
    }
}
