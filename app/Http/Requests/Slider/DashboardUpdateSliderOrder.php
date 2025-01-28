<?php

namespace App\Http\Requests\Slider;

use Illuminate\Foundation\Http\FormRequest;

class DashboardUpdateSliderOrder extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'orderedIds' => 'required|array',
            'orderedIds.*.id' => 'required|integer|exists:slider,id',
            'orderedIds.*.order' => 'required|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'orderedIds.required' => 'The orderedIds field is required.',
            'orderedIds.array' => 'The orderedIds field must be an array.',
            'orderedIds.*.id.required' => 'Each item must contain an id.',
            'orderedIds.*.id.integer' => 'The id must be an integer.',
            'orderedIds.*.id.exists' => 'The specified id does not exist in slider.',
            'orderedIds.*.order.required' => 'Each item must contain an order.',
            'orderedIds.*.order.integer' => 'The order must be an integer.',
            'orderedIds.*.order.min' => 'The order must be at least 1.',
        ];
    }
}
