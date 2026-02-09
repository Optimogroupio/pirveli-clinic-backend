<?php

namespace App\Http\Requests\News;

use App\Http\Requests\TranslatableRequest;

class DashboardStoreNewsRequest extends TranslatableRequest
{
    public function authorize(): true
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|unique:news,title',
            'description' => 'string|nullable',
            'service_id' => 'required|exists:services,id',
            'doctors' => 'nullable|array',
            'doctors.*' => 'exists:doctors,id',
            'image' => 'required|mimes:jpg,jpeg,png,svg,webp|max:2048',
            'meta_title' => 'string|nullable',
            'meta_description' => 'string|nullable',
        ];

        return $this->addTranslatableRules(['title', 'description', 'meta_title', 'meta_description'], $rules);
    }
}
