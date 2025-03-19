<?php

namespace App\GraphQL\Builders;

use App\Models\Slider;
use App\Traits\HandlesLocale;
use Illuminate\Database\Eloquent\Builder;

class SliderBuilder extends Builder
{
    use HandlesLocale;

    public function filterSlider($root, array $args): Builder
    {
        return Slider::query()->when($args['position'], fn ($query, $position) => $query->where('position', $position))->orderBy('sort_order');
    }
}
