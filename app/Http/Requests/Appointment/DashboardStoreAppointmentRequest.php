<?php

namespace App\Http\Requests\Appointment;

use App\Http\Requests\TranslatableRequest;

class DashboardStoreAppointmentRequest extends TranslatableRequest
{
    public function authorize(): true
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => 'required|string',
            'phone' => 'required|string',
            'comment' => 'required|string',
        ];
    }
}
