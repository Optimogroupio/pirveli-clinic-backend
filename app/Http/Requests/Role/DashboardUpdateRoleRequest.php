<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class DashboardUpdateRoleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|unique:roles,name,' . $this->route('role'),
            'guard_name' => 'required',
            'permissions' => 'required|array'
        ];
    }
}
