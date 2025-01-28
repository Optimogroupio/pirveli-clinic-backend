<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class DashboardStoreSettingsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'key' => 'required|string',
            'value' => 'nullable|string',
            'banner_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ];
    }
}
