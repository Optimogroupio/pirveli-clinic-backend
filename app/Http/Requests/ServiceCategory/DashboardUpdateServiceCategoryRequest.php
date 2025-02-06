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
            'name' => 'required|string|unique:service_categories,name,' . $this->route('service_category')
        ];

        return $this->addTranslatableRules(['name'], $rules);
    }

    /**
     * Specify the translatable attributes.
     */
    protected function translatableAttributes(): array
    {
        return ['name'];
    }
}
