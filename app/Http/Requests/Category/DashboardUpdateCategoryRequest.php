<?php

namespace App\Http\Requests\Category;

use App\Http\Requests\TranslatableRequest;

class DashboardUpdateCategoryRequest extends TranslatableRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|unique:categories,name,' . $this->route('news'),
            'description' => 'string|nullable',
            'video_iframe' => 'string|nullable',
            'services' => 'nullable|array',
            'meta_title' => 'string|nullable',
            'meta_description' => 'string|nullable',
        ];

        return $this->addTranslatableRules(['name', 'description', 'meta_title', 'meta_description'], $rules);
    }
}
