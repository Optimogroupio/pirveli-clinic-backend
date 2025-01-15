<?php

namespace App\Traits;

use App\Models\Locale;
use App\Models\Translation;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

trait Translatable
{
    public $translationsToSave = [];

    public function initializeTranslatable()
    {
        if (!in_array('translatable_attributes', $this->appends)) {
            $this->appends[] = 'translatable_attributes';
        }
    }

    public static function bootTranslatable()
    {
        static::saving(function ($model) {
            $locales = Locale::where('is_default', 0)->pluck('code')->toArray(); // Non-default locales
            foreach ($model->translatableAttributes as $attribute) {
                $defaultValue = request($attribute);
                if ($defaultValue) {
                    $model->{$attribute} = $defaultValue;
                }

                foreach ($locales as $locale) {
                    $translatedValue = request("Translatable.$locale.$attribute");
                    if ($translatedValue) {
                        $model->translationsToSave[$locale][$attribute] = $translatedValue;
                    }
                }
            }
        });

        static::saved(function ($model) {
            foreach ($model->translationsToSave as $locale => $translations) {
                foreach ($translations as $key => $value) {
                    $model->translations()->updateOrCreate(
                        [
                            'locale' => $locale,
                            'key' => $key,
                        ],
                        ['value' => $value]
                    );
                }
            }
        });
    }

    public function translations()
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    public function getAttribute($key)
    {
        // Check if the attribute is translatable
        if (isset($this->translatableAttributes) && in_array($key, $this->translatableAttributes)) {
            $locale = App::getLocale();
            $defaultLocale = Locale::where('is_default', 1)->first()->code;

            if ($locale !== $defaultLocale) {
                $translation = $this->translations->where('locale', $locale)->where('key', $key)->first();
                return $translation ? $translation->value : parent::getAttribute($key);
            }
        }

        // Fallback to the parent attribute value
        return parent::getAttribute($key);
    }

    public function translateContent($locale)
    {
        return new class($this, $locale) {
            private $model;
            private $locale;

            public function __construct($model, $locale)
            {
                $this->model = $model;
                $this->locale = $locale;
            }

            public function __get($attribute)
            {
                $defaultLocale = Locale::where('is_default', 1)->first()->code;

                if ($this->locale === $defaultLocale) {
                    return $this->model->{$attribute};
                }

                $translation = $this->model->translations->where('locale', $this->locale)->where('key', $attribute)->first();
                return $translation ? $translation->value : $this->model->{$attribute};
            }
        };
    }

    public function getTranslatableAttributesAttribute()
    {
        return $this->translatableAttributes ?? [];
    }

    /**
     * Filter the query by a translated field.
     *
     * @param Builder $query
     * @param string $locale
     * @param string $defaultLocale
     * @param string $field
     * @param string $value
     * @return Builder
     */
    public function scopeFilterByTranslatedField(Builder $query, string $locale, string $defaultLocale, string $field, string $value): Builder
    {
        if ($locale !== $defaultLocale) {
            return $query->whereHas('translations', function (Builder $q) use ($locale, $field, $value) {
                $q->where('locale', $locale)
                    ->where('key', $field)
                    ->where('value', 'like', '%' . $value . '%');
            });
        } else {
            return $query->where($field, 'like', '%' . $value . '%');
        }
    }
}
