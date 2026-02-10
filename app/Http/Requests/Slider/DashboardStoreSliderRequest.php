<?php

namespace App\Http\Requests\Slider;

use App\Http\Requests\TranslatableRequest;

class DashboardStoreSliderRequest extends TranslatableRequest
{
    public function authorize(): true
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'title' => 'nullable|string',
            'description' => 'string|nullable|min:100',
            'position' => 'string|required|in:top,bottom',
            'url' => 'nullable|string|url',
            'image' => 'required|mimes:webp|max:2048'
        ];


        return $this->addTranslatableRules(['title', 'description'], $rules);
    }
}
