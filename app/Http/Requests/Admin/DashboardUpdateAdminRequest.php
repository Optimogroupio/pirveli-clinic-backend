<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DashboardUpdateAdminRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'login' => 'sometimes|string|unique:dashboard_users,login,' . $this->route('administrator'),
            'email' => 'sometimes|email|unique:dashboard_users,email,' . $this->route('administrator'),
            'password' => 'sometimes|nullable|min:8|max:255',
            'super_admin' => 'sometimes|nullable|boolean',
            'roles' => 'sometimes|nullable|array',
            'permissions' => 'sometimes|nullable|array'
        ];


    }
}
