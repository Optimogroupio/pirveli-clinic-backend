<?php

namespace App\Models;

use App\Traits\HasAttachments;
use App\Traits\Translatable;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
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
        'meta_title',
        'meta_description'
    ];

    public $translatableAttributes = ['name', 'description', 'meta_title', 'meta_description'];

    public function image()
    {
        return $this->attachOne('image');
    }
}
