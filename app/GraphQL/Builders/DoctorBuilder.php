<?php

namespace App\GraphQL\Builders;

use App\Models\Doctor;
use App\Models\Locale;
use App\Traits\HandlesLocale;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;

class DoctorBuilder
{
    use HandlesLocale;

    public function filterDoctors($root, array $args): Builder
    {
        [$locale, $defaultLocale] = $this->initializeLocale($args);

        $query = Doctor::query();

        if (!empty($args['query'])) {
            $search = $args['query'];

            $query->where(function (Builder $q) use ($search, $locale, $defaultLocale) {

                // 🔍 Doctor full_name (translated)
                $q->filterByTranslatedField(
                    $locale,
                    $defaultLocale,
                    'full_name',
                    $search
                );

                // 🔍 Specialty name (translated relation)
                $q->orWhereHas('specialties', function (Builder $sq) use ($search, $locale, $defaultLocale) {
                    $sq->filterByTranslatedField(
                        $locale,
                        $defaultLocale,
                        'name',
                        $search
                    );
                });
            });
        }

        if (isset($args['specialty_id'])) {
            $query->whereHas('specialties', function (Builder $q) use ($args) {
                $q->where('doctor_specialties.specialty_id', $args['specialty_id']);
            });
        }

        if (isset($args['specialty_name'])) {
            $query->whereHas('specialties', function (Builder $q) use ($args, $locale, $defaultLocale) {
                $q->filterByTranslatedField($locale, $defaultLocale, 'name', $args['specialty_name']);
            });
        }

        return $query->orderBy('sort_order');
    }
}
