<?php

namespace App\Http\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;

class DashboardUpdatePermissionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|unique:permissions,name,' . $this->route('permission'),
            'guard_name' => 'required|string|in:web,dashboard',
        ];
    }
}
