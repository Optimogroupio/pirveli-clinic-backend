<?php

namespace App\Http\Requests\Language;

use App\Http\Requests\TranslatableRequest;

class DashboardUpdateLanguageRequest extends TranslatableRequest
{
    public function authorize(): true
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|unique:languages,name,' . $this->route('language')
        ];

        return $this->addTranslatableRules(['name'], $rules);
    }
}
