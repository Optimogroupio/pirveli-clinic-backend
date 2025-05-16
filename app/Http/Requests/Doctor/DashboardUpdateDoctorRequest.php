<?php

namespace App\Http\Requests\Doctor;

use App\Http\Requests\TranslatableRequest;
use App\Rules\FileAttachment;

class DashboardUpdateDoctorRequest extends TranslatableRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'full_name' => 'required|string',
            'service_id' => 'required|exists:services,id',
            'specialties' => 'required|array',
            'languages' => 'required|array',
            'image' => ['nullable', new FileAttachment(), 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'meta_title' => 'string|nullable',
            'meta_description' => 'string|nullable'
        ];

        return $this->addTranslatableRules(['full_name', 'position', 'meta_title', 'meta_description'], $rules);
    }
}
