<?php

namespace App\Http\Requests\Locale;

use Illuminate\Foundation\Http\FormRequest;

class DashboardStoreLocaleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|unique:locales,name',
            'code' => 'required|string',
            'default' => 'required|boolean',
        ];
    }

}
