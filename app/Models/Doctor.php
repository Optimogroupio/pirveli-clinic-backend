<?php

namespace App\Models;

use App\Traits\HasAttachments;
use App\Traits\Translatable;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Doctor extends Model
{
    use Sluggable, Translatable, HasAttachments;

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'full_name'
            ]
        ];
    }

    protected $fillable = [
        'full_name',
        'position',
        'service_id',
        'meta_title',
        'meta_description',
        'sort_order',
    ];

    public $translatableAttributes = ['full_name', 'meta_title', 'meta_description'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function specialties()
    {
        return $this->belongsToMany(Specialty::class, 'doctor_specialties');
    }

    public function news()
    {
        return $this->belongsToMany(News::class, 'doctor_news');
    }

    public function doctorDetails()
    {
        return $this->hasMany(DoctorDetail::class);
    }

    public function educations()
    {
        return $this->doctorDetails()->education()->orderBy('sort_order');
    }

    public function experiences()
    {
        return $this->doctorDetails()->experience()->orderBy('sort_order');
    }

    public function certificates()
    {
        return $this->doctorDetails()->certificate()->orderBy('sort_order');
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'doctor_languages')->withTimestamps();
    }

    public function image()
    {
        return $this->attachOne('image');
    }
}
