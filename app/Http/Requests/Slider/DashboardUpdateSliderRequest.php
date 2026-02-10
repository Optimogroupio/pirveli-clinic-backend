<?php

namespace App\Http\Requests\Slider;

use App\Http\Requests\TranslatableRequest;
use App\Rules\FileAttachment;

class DashboardUpdateSliderRequest extends TranslatableRequest
{
    public function authorize(): true
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'title' => 'nullable|string',
            'description' => 'nullable|string|min:100',
            'position' => 'string|required|in:top,bottom',
            'url' => 'nullable|string|url',
            'image' => ['nullable', new FileAttachment()],
        ];

        if (!$this->file('image')) {
            $rules['image'][] = 'required';
        }

        if ($this->file('image')) {
            $rules['image'][] = 'mimes:webp';
            $rules['image'][] = 'max:2048';
        }

        return $this->addTranslatableRules(['title', 'description'], $rules);
    }
}
