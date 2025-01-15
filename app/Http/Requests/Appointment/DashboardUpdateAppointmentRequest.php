<?php

namespace App\Http\Requests\Appointment;

use App\Http\Requests\TranslatableRequest;

class DashboardUpdateAppointmentRequest extends TranslatableRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string',
            'surname' => 'required|string',
            'specialty_id' => 'nullable|exists:specialties,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'phone' => 'required|string',
        ];

    }
}
