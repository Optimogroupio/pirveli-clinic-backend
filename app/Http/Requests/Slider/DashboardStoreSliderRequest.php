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
            'title' => 'required|string|unique:news,title',
            'description' => 'string|required|min:100',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ];

        return $this->addTranslatableRules(['title', 'description'], $rules);
    }
}
