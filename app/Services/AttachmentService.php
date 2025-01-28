<?php

namespace App\Services;

use App\Models\FileAttachment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AttachmentService
{
    /**
     * Attach a single file to a model dynamically.
     *
     * @param Model $model
     * @param mixed $file
     * @param string $fieldName
     * @param string|null $directory
     * @return FileAttachment
     */
    public function attachFile(Model $model, $file, string $fieldName, ?string $directory): FileAttachment
    {
        $directory = $directory ?? $model->getTable() . '/' . $fieldName;

        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

        $relativePath = $file->storeAs($directory, $fileName, 'public');

        $originalFileName = $file->getClientOriginalName();

        return $model->attachOne($fieldName)->create([
            'path' => $relativePath,
            'file_name' => $originalFileName,
            'disk_name' => $fileName,
            'field' => $fieldName,
            'attachment_type' => $file->getMimeType() ?? 'unknown',
            'file_size' => $file->getSize(),
            'content_type' => $file->getMimeType(),
        ]);
    }

    /**
     * Attach multiple files to a model.
     *
     * @param Model $model
     * @param array $files
     * @param string $fieldName
     * @param string|null $directory
     * @return array
     */
    public function attachMultipleFiles(Model $model, array $files, string $fieldName, string $directory = null): array
    {
        $attachments = [];

        foreach ($files as $file) {
            $attachments[] = $this->attachFile($model, $file, $fieldName, $directory);
        }

        return $attachments;
    }

    /**
     * Delete an attached file.
     *
     * @param FileAttachment $attachment
     * @return bool
     */
    public function deleteAttachment(FileAttachment $attachment): bool
    {
        Storage::disk('public')->delete($attachment->path);

        return $attachment->delete();
    }


    /**
     * Get the full URL of the attached file.
     *
     * @param FileAttachment $attachment
     * @return string
     */
    public function getFileUrl(FileAttachment $attachment): string
    {
        return Storage::url($attachment->path);
    }
}
