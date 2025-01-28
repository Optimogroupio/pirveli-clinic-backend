<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;
use App\Models\FileAttachment as FileAttachmentModel;

class FileAttachment implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value instanceof UploadedFile) {
            if (!$value->isValid()) {
                $fail(__('validation.invalid_uploaded_file'));
            }
            return;
        }

        if (is_array($value)) {
            if (isset($value['id'])) {
                $file = FileAttachmentModel::find($value['id']);
                if (!$file) {
                    $fail(__('validation.file_attachment_not_found', ['id' => $value['id']]));
                }
            } else {
                $fail(__('validation.file_attachment_missing_id'));
            }
            return;
        }

        if (is_object($value)) {
            if (isset($value->id)) {
                $file = FileAttachmentModel::find($value->id);
                if (!$file) {
                    $fail(__('validation.file_attachment_not_found', ['id' => $value->id]));
                }
            } else {
                $fail(__('validation.file_attachment_missing_id'));
            }
            return;
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $fail(__('validation.invalid_json_format'));
                return;
            }

            if (isset($decoded['id'])) {
                $file = FileAttachmentModel::find($decoded['id']);
                if (!$file) {
                    $fail(__('validation.file_attachment_not_found', ['id' => $decoded['id']]));
                }
            } else {
                $fail(__('validation.file_attachment_missing_id'));
            }
            return;
        }

        $fail(__('validation.invalid_file_attachment'));
    }
}
