<?php

namespace App\Http\Requests\Doctor;

use App\Http\Requests\TranslatableRequest;

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
            'image' => 'file_attachment',
            'meta_title' => 'string|nullable',
            'meta_description' => 'string|nullable'
        ];

        return $this->addTranslatableRules(['full_name', 'position', 'meta_title', 'meta_description'], $rules);
    }
}
