<?php

namespace App\Http\Requests\Language;

use App\Http\Requests\TranslatableRequest;

class DashboardUpdateLanguageRequest extends TranslatableRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|unique:languages,name,' . $this->route('language')
        ];

        return $this->addTranslatableRules(['name'], $rules);
    }
}
