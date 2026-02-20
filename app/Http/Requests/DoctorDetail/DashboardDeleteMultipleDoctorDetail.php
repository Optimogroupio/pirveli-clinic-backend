<?php

namespace App\Http\Requests\DoctorDetail;

use Illuminate\Foundation\Http\FormRequest;

class DashboardDeleteMultipleDoctorDetail extends FormRequest
{
    public function authorize(): true
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ids' => 'required|array'
        ];
    }
}
