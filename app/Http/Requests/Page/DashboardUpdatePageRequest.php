<?php

namespace App\Http\Requests\Page;

use App\Http\Requests\TranslatableRequest;

class DashboardUpdatePageRequest extends TranslatableRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|unique:pages,name,' . $this->route('page'),
            'description' => 'string|nullable',
            'image' => 'file_attachment',
            'meta_title' => 'string|nullable',
            'meta_description' => 'string|nullable',
        ];

        return $this->addTranslatableRules(['name', 'description', 'meta_title', 'meta_description'], $rules);
    }
}
