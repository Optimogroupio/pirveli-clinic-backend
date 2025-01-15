<?php

namespace App\Http\Requests\Specialty;

use App\Http\Requests\TranslatableRequest;

class DashboardStoreSpecialtyRequest extends TranslatableRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|unique:specialties,name',
        ];

        return $this->addTranslatableRules(['name'], $rules);
    }
}
