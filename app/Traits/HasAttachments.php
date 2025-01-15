<?php

namespace App\Traits;

use App\Models\FileAttachment;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasAttachments
{
    public function attachOne($relationName): MorphOne
    {
        return $this->morphOne(FileAttachment::class, 'attachment')->where('field', $relationName);
    }

    public function attachMany($relationName): MorphMany
    {
        return $this->morphMany(FileAttachment::class, 'attachment')->where('field', $relationName);
    }
}
