<?php

namespace App\Http\Requests\Slider;

use App\Http\Requests\TranslatableRequest;
use App\Rules\FileAttachment;
use ProtoneMedia\Splade\FileUploads\HasSpladeFileUploads;

class DashboardUpdateSliderRequest extends TranslatableRequest
    {
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'title' => 'nullable|string',
            'description' => 'nullable|string|min:100',
            'position' => 'string|required|in:top,bottom',
            'opens_modal' => 'nullable|integer|in:0,1',
            'button_url' => 'nullable|string|url',
            'button_title' => 'nullable|string',
            'image' => ['nullable', new FileAttachment(), 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];

        return $this->addTranslatableRules(['title', 'description'], $rules);
    }
}
