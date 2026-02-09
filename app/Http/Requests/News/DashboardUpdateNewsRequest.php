<?php

namespace App\Http\Requests\News;

use App\Http\Requests\TranslatableRequest;
use App\Rules\FileAttachment;

class DashboardUpdateNewsRequest extends TranslatableRequest
{
    public function authorize(): true
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|unique:news,title,' . $this->route('news'),
            'description' => 'string|nullable',
            'service_id' => 'required|exists:services,id',
            'doctors' => 'nullable|array',
            'doctors.*' => 'exists:doctors,id',
            'image' => ['nullable', new FileAttachment()],
            'meta_title' => 'string|nullable',
            'meta_description' => 'string|nullable',
        ];

        if (!$this->file('image')) {
            $rules['image'][] = 'required';
        }

        if ($this->file('image')) {
            $rules['image'][] = 'mimes:jpg,jpeg,png,svg,webp';
            $rules['image'][] = 'max:2048';
        }

        return $this->addTranslatableRules(['title', 'description', 'meta_title', 'meta_description'], $rules);
    }
}
