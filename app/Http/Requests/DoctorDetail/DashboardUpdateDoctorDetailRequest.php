<?php

namespace App\Http\Requests\DoctorDetail;

use App\Http\Requests\TranslatableRequest;

class DashboardUpdateDoctorDetailRequest extends TranslatableRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'doctor_id' => 'required|exists:doctors,id',
            'type' => 'required|string',
            'name' => 'required|string',
            'title' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required_if:to_this_day,false|date|nullable',
            'to_this_day' => 'required_without:end_date|boolean',
        ];

        return $this->addTranslatableRules(['name', 'title'], $rules);
    }
}
