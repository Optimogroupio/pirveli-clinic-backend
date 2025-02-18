<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileAttachment extends Model
{
    protected $fillable = ['file_name', 'disk_name', 'file_size', 'content_type', 'attachment_id', 'attachment_type', 'field', 'is_public'];

    protected $appends = ['url', 'content'];

    /**
     * Get the full URL of the stored file.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        $modelName = class_basename($this->attachment_type);

        $modelInstance = app("App\\Models\\{$modelName}");
        $tableName = $modelInstance->getTable();


        $dynamicPath = "{$tableName}/{$this->field}/{$this->disk_name}";
        return url(Storage::url($dynamicPath));
    }

    /**
     * Get file content directly.
     *
     * @return string|null
     */
    public function getContentAttribute()
    {
        $content = @file_get_contents($this->url);

        return $content ?? null;
    }



    public function getPathAttribute()
    {
        return Storage::url($this->disk_name);
    }

    public function getExtensionAttribute()
    {
        return pathinfo($this->file_name, PATHINFO_EXTENSION);
    }

    public static function fromUploadedFile($uploadedFile, $field = null)
    {
        $file = new self;
        $file->file_name = $uploadedFile->getClientOriginalName();
        $file->disk_name = $uploadedFile->store('uploads');
        $file->file_size = $uploadedFile->getSize();
        $file->content_type = $uploadedFile->getMimeType();
        $file->field = $field;
        $file->save();

        return $file;
    }

    public function deleteFile()
    {
        Storage::delete($this->disk_name);
        $this->delete();
    }
}
