<?php

namespace App\Http\Requests\Service;

use App\Http\Requests\TranslatableRequest;

class DashboardUpdateServiceOrderRequest extends TranslatableRequest
{
    public function authorize(): true
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'orderedIds' => 'required|array',
            'orderedIds.*.id' => 'required|integer|exists:services,id',
            'orderedIds.*.order' => 'required|integer|min:1',
        ];
    }
}
