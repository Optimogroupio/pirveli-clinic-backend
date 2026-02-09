<?php

namespace App\Http\Requests\Language;

use App\Http\Requests\TranslatableRequest;

class DashboardStoreLanguageRequest extends TranslatableRequest
{
    public function authorize(): true
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|unique:languages,name'
        ];

        return $this->addTranslatableRules(['name'], $rules);
    }
}
