<?php

namespace App\Http\Requests\Slider;

use App\Http\Requests\TranslatableRequest;
use App\Rules\FileAttachment;
use ProtoneMedia\Splade\FileUploads\HasSpladeFileUploads;

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

        return $this->addTranslatableRules(['title', 'description'], $rules);
    }
}
