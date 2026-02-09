<?php

namespace App\Http\Requests\Doctor;

use App\Http\Requests\TranslatableRequest;

class DashboardUpdateDoctorOrderRequest extends TranslatableRequest
{
    public function authorize(): true
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'orderedIds' => 'required|array',
            'orderedIds.*.id' => 'required|integer|exists:doctors,id',
            'orderedIds.*.order' => 'required|integer|min:1',
        ];
    }
}
