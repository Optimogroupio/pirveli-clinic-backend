<?php

namespace App\Http\Requests\Specialty;

use App\Http\Requests\TranslatableRequest;

class DashboardUpdateSpecialtyRequest extends TranslatableRequest
{
    public function authorize(): true
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string',
        ];

        return $this->addTranslatableRules(['name'], $rules);
    }
}
