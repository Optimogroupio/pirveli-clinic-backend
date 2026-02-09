<?php

namespace App\Http\Requests\ServiceCategory;

use App\Http\Requests\TranslatableRequest;

class DashboardStoreServiceCategoryRequest extends TranslatableRequest
{
    public function authorize(): true
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|unique:service_categories,name'
        ];

        return $this->addTranslatableRules(['name'], $rules);
    }
}
