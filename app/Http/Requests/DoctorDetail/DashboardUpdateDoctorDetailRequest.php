<?php

namespace App\Http\Requests\DoctorDetail;

use App\Http\Requests\TranslatableRequest;

class DashboardUpdateDoctorDetailRequest extends TranslatableRequest
{
    public function authorize(): true
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'doctor_id' => 'required|exists:doctors,id',
            'type' => 'required|string',
            'name' => 'required|string',
            'title' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'to_this_day' => 'nullable|boolean',
        ];

        return $this->addTranslatableRules(['name', 'title'], $rules);
    }
}
