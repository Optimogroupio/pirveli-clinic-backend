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
            'phone' => 'required|string',
            'comment' => 'required|string',
        ];

    }
}
