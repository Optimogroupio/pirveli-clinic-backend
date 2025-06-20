<?php

namespace App\Models;

use App\Traits\HasAttachments;
use App\Traits\Translatable;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use Sluggable, Translatable, HasAttachments;

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    protected $fillable = [
        'name',
        'slug',
        'description',
        'video_iframe',
        'meta_title',
        'meta_description'
    ];

    public array $translatableAttributes = ['name', 'description', 'meta_title', 'meta_description'];

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'category_services');
    }
}
