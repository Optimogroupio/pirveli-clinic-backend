<?php

namespace App\Http\Requests;

use App\Models\Locale;
use Illuminate\Foundation\Http\FormRequest;

abstract class TranslatableRequest extends FormRequest
{
    protected function addTranslatableRules(array $attributes, array $rules = []): array
    {
        // Add rules for each locale
        foreach (Locale::where('is_default', 0)->get() as $locale) {
            foreach ($attributes as $attribute) {
                $rules["Translatable.{$locale->code}.$attribute"] = 'nullable|string';
            }
        }

        return $rules;
    }

    /**
     * Ensure Translatable data is returned in validated data.
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated();

        // Merge Translatable fields, even if they are empty
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

    /**
     * Define which attributes are translatable.
     */
    protected function translatableAttributes(): array
    {
        return [];
    }
}
