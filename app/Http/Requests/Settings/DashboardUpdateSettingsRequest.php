<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class DashboardUpdateSettingsRequest extends FormRequest
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
            'banner_image' => 'nullable|file_attachment',
            'logo' => 'nullable|file_attachment'
        ];
    }
}
