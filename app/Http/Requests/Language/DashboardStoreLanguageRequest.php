<?php

namespace App\Http\Requests\Language;

use App\Http\Requests\TranslatableRequest;

class DashboardStoreLanguageRequest extends TranslatableRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|unique:languages,name'
        ];

        return $this->addTranslatableRules(['name'], $rules);
    }
}
