<?php

namespace App\Models;

use App\Traits\HasAttachments;
use App\Traits\Translatable;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use Sluggable, Translatable, HasAttachments;

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    protected $fillable = [
        'title',
        'slug',
        'description',
        'service_id',
        'meta_title',
        'meta_description'
    ];

    public $translatableAttributes = ['title', 'description', 'meta_title', 'meta_description'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'doctor_news');
    }

    public function image()
    {
        return $this->attachOne('image');
    }
}
