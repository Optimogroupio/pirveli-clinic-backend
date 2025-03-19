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
            'title' => 'required|string',
            'description' => 'string|required|min:100',
            'position' => 'string|required|in:top,bottom',
            'opens_modal' => 'nullable|integer|in:0,1',
            'button_url' => 'nullable|string|url',
            'button_title' => 'nullable|string',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ];

        return $this->addTranslatableRules(['title', 'description'], $rules);
    }
}
