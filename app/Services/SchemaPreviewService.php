<?php

namespace App\Services;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Schema Preview Service
 */
class SchemaPreviewService
{
    /**
     * @throws Exception
     */
    /**
     * Save Preview File
     *
     * @param UploadedFile $previewFile
     * @return string
     */
    public function saveFile(UploadedFile $previewFile): string
    {
        $fileName = $this->generateFileName($previewFile);

        $this->saveToStorage($previewFile, $fileName);

        return $fileName;
    }

    /**
     * Delete Preview File of Schema
     *
     * @param string $fileName
     * @return bool
     * @throws Exception
     */
    public function deleteFile(string $fileName): bool
    {
        return Storage::disk('schema_previews')->delete($fileName);
    }

    /**
     * Save Preview File
     *
     * @param UploadedFile $previewFile
     * @param string $fileName
     * @return string File Path
     */
    private function saveToStorage(UploadedFile $previewFile, string $fileName): string
    {
        return Storage::disk('schema_previews')->putFileAs('', $previewFile, $fileName);
    }

    /**
     * Generate File Name for Preview File
     *
     * @param UploadedFile $file
     * @return string
     */
    private function generateFileName(UploadedFile $file): string
    {
        return Str::random() . '.' . $file->getClientOriginalExtension();
    }
}
