<?php

namespace App\Models;

use App\Traits\HasAttachments;
use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use Translatable, HasAttachments;

    protected $table = 'slider';

    protected $fillable = [
        'title',
        'description',
        'sort_order'
    ];

    public $translatableAttributes = ['title', 'description'];

    public function image()
    {
        return $this->attachOne('image');
    }

}
