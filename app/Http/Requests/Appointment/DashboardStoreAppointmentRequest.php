<?php

namespace App\Http\Requests\Appointment;

use App\Http\Requests\TranslatableRequest;

class DashboardStoreAppointmentRequest extends TranslatableRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'full_name' => 'required|string',
            'phone' => 'required|string',
            'comment' => 'required|string',
        ];
    }
}
