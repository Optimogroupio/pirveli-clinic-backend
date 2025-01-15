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
    public function attachFile(Model $model, $file, string $fieldName)
    {
        // Dynamically create the directory based on the model and field name
        $directory = $directory ?? $model->getTable() . '/' . $fieldName;

        // Generate a unique file name with extension
        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

        // Store the file using the generated unique name in the dynamic directory
        $relativePath = $file->storeAs($directory, $fileName, 'public');

        // Retrieve the original file name from the uploaded file
        $originalFileName = $file->getClientOriginalName();

        // Create and associate the attachment record
        return $model->attachOne($fieldName)->create([
            'path' => $relativePath, // Store the relative path (e.g., news/images/<file>)
            'file_name' => $originalFileName,
            'disk_name' => $fileName, // Unique file name
            'field' => $fieldName,
            'attachment_type' => $file->getMimeType() ?? 'unknown', // Detect type dynamically
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
    public function attachMultipleFiles(Model $model, array $files, string $fieldName, string $directory = null)
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
    public function deleteAttachment(FileAttachment $attachment)
    {
        // Delete the file from storage
        Storage::disk('public')->delete($attachment->path);

        // Delete the attachment record
        return $attachment->delete();
    }

    /**
     * Get the full URL of the attached file.
     *
     * @param FileAttachment $attachment
     * @return string
     */
    public function getFileUrl(FileAttachment $attachment)
    {
        return Storage::url($attachment->path); // Use the relative path to generate the URL
    }
}
