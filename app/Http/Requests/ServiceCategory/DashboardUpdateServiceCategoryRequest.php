<?php

namespace App\Http\Requests\ServiceCategory;

use App\Http\Requests\TranslatableRequest;

class DashboardUpdateServiceCategoryRequest extends TranslatableRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|unique:service_categories,name,' . $this->route('service_category'),
            'description' => 'string|nullable',
            'meta_title' => 'string|nullable',
            'meta_description' => 'string|nullable',
        ];

        return $this->addTranslatableRules(['name', 'description', 'meta_title', 'meta_description'], $rules);
    }

    /**
     * Specify the translatable attributes.
     */
    protected function translatableAttributes(): array
    {
        return ['name', 'description'];
    }
}
