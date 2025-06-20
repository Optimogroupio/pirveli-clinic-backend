<?php

namespace App\GraphQL\Builders;

use App\Models\Doctor;
use App\Models\Locale;
use App\Models\Service;
use App\Traits\HandlesLocale;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;

class ServiceBuilder extends Builder
{
    use HandlesLocale;

    public function filterServices($root, array $args): Builder
    {
        [$locale, $defaultLocale] = $this->initializeLocale($args);

        $query = Service::query();

        if (isset($args['name'])) {
            $query->filterByTranslatedField($locale, $defaultLocale, 'name', $args['name']);
        }

        if (isset($args['description'])) {
            $query->filterByTranslatedField($locale, $defaultLocale, 'description', $args['description']);
        }

        if (isset($args['service_category_id'])) {
            $query->where('service_category_id', $args['service_category_id']);
        }

        if (!empty($args['category_slug'])) {
            $query->whereHas('categories', function ($q) use ($args) {
                $q->where('slug', $args['category_slug']);
            });
        }

        return $query->orderBy('sort_order');
    }
}
