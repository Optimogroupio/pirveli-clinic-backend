<?php

namespace App\Traits;

use App\Models\Locale;
use Illuminate\Support\Facades\App;

trait HandlesLocale
{
    /**
     * Sets the application locale from the provided arguments.
     *
     * @param array $args
     * @return array [$locale, $defaultLocale]
     */
    protected function initializeLocale(array $args): array
    {
        App::setLocale($args['locale']);

        $locale = App::getLocale();
        $defaultLocale = cache()->remember('default_locale', 3600, function () {
            return Locale::where('is_default', true)->value('code');
        });

        return [$locale, $defaultLocale];
    }
}
