<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    use Translatable;

    protected $fillable = [
        'name'
    ];

    public $translatableAttributes = ['name'];

}
