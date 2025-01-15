<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use Translatable;

    protected $fillable = [
        'name',
    ];

    public $translatableAttributes = ['name'];

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'doctor_languages')->withTimestamps();
    }

}
