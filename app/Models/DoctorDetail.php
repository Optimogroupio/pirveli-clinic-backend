<?php

namespace App\Models;

use App\Traits\Translatable;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class DoctorDetail extends Model
{
    use Sluggable, Translatable;

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'full_name'
            ]
        ];
    }

    protected $fillable = [
        'doctor_id',
        'type',
        'name',
        'title',
        'start_date',
        'end_date',
        'to_this_day',
        'sort_order'
    ];

    public $translatableAttributes = ['name', 'title'];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function scopeEducation($query)
    {
        return $query->where('type', 'education');
    }

    public function scopeExperience($query)
    {
        return $query->where('type', 'experience');
    }

    public function scopeCertificate($query)
    {
        return $query->where('type', 'certificate');
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->type = self::modifyTypeLastWord($model->type);
            if ($model->to_this_day === 1) {
                $model->end_date = null;
            }
        });

        self::updating(function ($model) {
            $model->type = self::modifyTypeLastWord($model->type);
            if ($model->to_this_day === 1) {
                $model->end_date = null;
            }
        });
    }

    /**
     * Removes last word from type if needed
     * @param string $type
     * @return string
     */
    public static function modifyTypeLastWord(string $type)
    {
        switch ($type) {
            case 'educations':
                return 'education';
            case 'experiences':
                return 'experience';
            case 'certificates':
                return 'certificate';
            default:
                return $type;
        }
    }

}
