<?php

namespace App\Http\Requests\Service;

use App\Http\Requests\TranslatableRequest;

class DashboardStoreServiceRequest extends TranslatableRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|unique:service_categories,name',
            'short_description' => 'required|string',
            'description' => 'string|nullable',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'service_category_id' => 'required|exists:service_categories,id',
            'meta_title' => 'string|nullable',
            'meta_description' => 'string|nullable',
        ];

        return $this->addTranslatableRules(['name', 'short_description', 'description', 'meta_title', 'meta_description'], $rules);
    }
}
