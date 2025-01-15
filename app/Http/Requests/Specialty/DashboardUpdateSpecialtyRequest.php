<?php

namespace App\Http\Requests\Specialty;

use App\Http\Requests\TranslatableRequest;

class DashboardUpdateSpecialtyRequest extends TranslatableRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string',
        ];

        return $this->addTranslatableRules(['name'], $rules);
    }
}
