<?php

namespace App\Http\Requests;

use App\Models\Locale;
use Illuminate\Foundation\Http\FormRequest;

abstract class TranslatableRequest extends FormRequest
{
    protected function addTranslatableRules(array $attributes, array $rules = []): array
    {
        foreach (Locale::where('is_default', 0)->get() as $locale) {
            foreach ($attributes as $attribute) {
                $rules["Translatable.{$locale->code}.$attribute"] = 'nullable|string';
            }
        }

        return $rules;
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated();

        foreach (Locale::where('is_default', 0)->get() as $locale) {
            foreach ($this->translatableAttributes() as $attribute) {
                $localeCode = $locale->code;
                if (!isset($validated['Translatable'][$localeCode][$attribute]) && $this->has("Translatable.$localeCode.$attribute")) {
                    $validated['Translatable'][$localeCode][$attribute] = $this->input("Translatable.$localeCode.$attribute");
                }
            }
        }

        return $validated;
    }

    protected function translatableAttributes(): array
    {
        return [];
    }
}
