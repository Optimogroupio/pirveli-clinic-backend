<?php

namespace App\Models;

use App\Traits\HasAttachments;
use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use Translatable, HasAttachments;

    protected $fillable = [
        'key',
        'value'
    ];

    public $translatableAttributes = ['key', 'value'];

    public function banner_image()
    {
        return $this->attachOne('banner_image');
    }

    public function logo()
    {
        return $this->attachOne('logo');
    }

}
