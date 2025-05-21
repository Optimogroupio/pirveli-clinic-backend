<?php

namespace App\Models;

use App\Traits\HasAttachments;
use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Slider extends Model
{
    use Translatable, HasAttachments;

    protected $table = 'slider';

    protected $fillable = [
        'title',
        'description',
        'position',
        'url',
        'sort_order'
    ];

    public array $translatableAttributes = ['title', 'description'];

    public function image(): MorphOne
    {
        return $this->attachOne('image');
    }

}
