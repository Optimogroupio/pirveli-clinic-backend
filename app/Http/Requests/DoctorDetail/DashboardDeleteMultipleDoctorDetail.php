<?php

namespace App\Http\Requests\DoctorDetail;

use Illuminate\Foundation\Http\FormRequest;

class DashboardDeleteMultipleDoctorDetail extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'ids' => 'required|array',
            'orderedIds.*' => 'required|integer|exists:doctor_details,id',
        ];
    }

    public function messages()
    {
        return [
            'orderedIds.required' => 'The ids field is required.',
            'orderedIds.array' => 'The ids field must be an array.',
            'orderedIds.*.required' => 'Each item must contain an id.',
            'orderedIds.*.integer' => 'The id must be an integer.',
            'orderedIds.*.exists' => 'The specified id does not exist in doctor details.',
        ];
    }
}
