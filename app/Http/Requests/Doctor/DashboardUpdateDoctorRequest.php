<?php

namespace App\Http\Requests\Doctor;

use App\Http\Requests\TranslatableRequest;
use App\Rules\FileAttachment;

class DashboardUpdateDoctorRequest extends TranslatableRequest
{
    public function authorize(): true
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'full_name' => 'required|string',
            'service_id' => 'required|exists:services,id',
            'specialties' => 'required|array',
            'languages' => 'required|array',
            'image' => [new FileAttachment()],
            'meta_title' => 'string|nullable',
            'meta_description' => 'string|nullable'
        ];

        if (!$this->file('image')) {
            $rules['image'][] = 'required';
        }

        if ($this->file('image')) {
            $rules['image'][] = 'mimes:jpg,jpeg,png,svg,webp';
            $rules['image'][] = 'max:2048';
        }

        return $this->addTranslatableRules(['full_name', 'position', 'meta_title', 'meta_description'], $rules);
    }
}
