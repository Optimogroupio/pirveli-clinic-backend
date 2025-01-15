<?php

namespace App\Http\Requests\ServiceCategory;

use App\Http\Requests\TranslatableRequest;

class DashboardStoreServiceCategoryRequest extends TranslatableRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|unique:service_categories,name',
            'description' => 'string|nullable',
            'meta_title' => 'string|nullable',
            'meta_description' => 'string|nullable',
        ];

        return $this->addTranslatableRules(['name', 'description', 'meta_title', 'meta_description'], $rules);
    }
}
