<?php

namespace App\Http\Requests\DoctorDetail;

use App\Http\Requests\TranslatableRequest;
use Carbon\Carbon;

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

    protected function prepareForValidation(): void
    {
        $this->merge([
            'start_date' => $this->start_date ? Carbon::parse($this->start_date)->startOfDay() : null,
            'end_date' => $this->end_date ? Carbon::parse($this->end_date)->endOfDay() : null,
        ]);
    }
}
