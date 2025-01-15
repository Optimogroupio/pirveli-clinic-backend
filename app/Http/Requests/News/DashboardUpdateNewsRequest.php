<?php

namespace App\Http\Requests\News;

use App\Http\Requests\TranslatableRequest;

class DashboardUpdateNewsRequest extends TranslatableRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'title' => 'required|string|unique:news,title,' . $this->route('news'),
            'description' => 'string|nullable',
            'service_id' => 'required|exists:services,id',
            'doctors' => 'required|array',
            'doctors.*' => 'exists:doctors,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'meta_title' => 'string|nullable',
            'meta_description' => 'string|nullable',
        ];

        return $this->addTranslatableRules(['title', 'description', 'meta_title', 'meta_description'], $rules);
    }
}
