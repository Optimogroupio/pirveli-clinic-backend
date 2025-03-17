<?php

namespace App\Http\Requests\Service;

use App\Http\Requests\TranslatableRequest;

class DashboardUpdateServiceOrderRequest extends TranslatableRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'orderedIds' => 'required|array',
            'orderedIds.*.id' => 'required|integer|exists:services,id',
            'orderedIds.*.order' => 'required|integer|min:1',
        ];
    }
}
