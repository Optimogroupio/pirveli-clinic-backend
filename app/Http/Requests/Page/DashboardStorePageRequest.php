<?php

namespace App\Http\Requests\Page;

use App\Http\Requests\TranslatableRequest;

class DashboardStorePageRequest extends TranslatableRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|unique:pages,name',
            'description' => 'string|nullable',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'meta_title' => 'string|nullable',
            'meta_description' => 'string|nullable',
        ];

        return $this->addTranslatableRules(['name', 'description', 'meta_title', 'meta_description'], $rules);
    }
}
