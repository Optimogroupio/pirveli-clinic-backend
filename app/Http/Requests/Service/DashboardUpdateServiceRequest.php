<?php

namespace App\Http\Requests\Service;

use App\Http\Requests\TranslatableRequest;
use App\Rules\FileAttachment;

class DashboardUpdateServiceRequest extends TranslatableRequest
{
    public function authorize(): true
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|unique:service_categories,name,' . $this->route('service_category'),
            'short_description' => 'required|string',
            'description' => 'string|nullable',
            'image' => ['nullable', new FileAttachment()],
            'service_category_id' => 'required|exists:service_categories,id',
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

        return $this->addTranslatableRules(['name', 'short_description', 'description', 'meta_title', 'meta_description'], $rules);
    }
}
