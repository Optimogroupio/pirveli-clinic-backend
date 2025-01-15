<?php

namespace App\Models;

use App\Traits\Translatable;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use Sluggable, Translatable;

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
        'svg',
        'service_category_id',
        'meta_title',
        'meta_description'
    ];

    public $translatableAttributes = ['name', 'description', 'meta_title', 'meta_description'];

    public function service_category()
    {
        return $this->belongsTo(ServiceCategory::class);
    }

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    public function news()
    {
        return $this->hasMany(News::class);
    }

}
