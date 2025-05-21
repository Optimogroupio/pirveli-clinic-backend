<?php

namespace App\Http\Requests\Slider;

use App\Http\Requests\TranslatableRequest;

class DashboardStoreSliderRequest extends TranslatableRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'title' => 'nullable|string',
            'description' => 'string|nullable|min:100',
            'position' => 'string|required|in:top,bottom',
            'url' => 'nullable|string|url',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048'
        ];

        return $this->addTranslatableRules(['title', 'description'], $rules);
    }
}
