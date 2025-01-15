<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DashboardStoreAdminRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'login' => 'required|string|unique:dashboard_users,login',
            'email' => 'required|email|unique:dashboard_users,email',
            'password' => 'required|min:8|max:255',
            'super_admin' => 'sometimes|nullable|boolean',
            'roles' => 'sometimes|nullable|array',
            'permissions' => 'sometimes|nullable|array'
        ];
    }
}
