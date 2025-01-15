<?php

namespace App\Http\Requests\DoctorDetail;

use App\Http\Requests\TranslatableRequest;
use Carbon\Carbon;

class DashboardStoreDoctorDetailRequest extends TranslatableRequest
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

    protected function prepareForValidation()
    {
        $this->merge([
            'start_date' => $this->start_date ? Carbon::parse($this->start_date)->startOfDay() : null,
            'end_date' => $this->end_date ? Carbon::parse($this->end_date)->endOfDay() : null,
        ]);
    }
}
