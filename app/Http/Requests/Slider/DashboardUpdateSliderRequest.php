<?php

namespace App\Http\Requests\Slider;

use App\Http\Requests\TranslatableRequest;
use ProtoneMedia\Splade\FileUploads\HasSpladeFileUploads;

class DashboardUpdateSliderRequest extends TranslatableRequest
    {
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'title' => 'nullable|string|unique:slider,title,' . $this->route('slider'),
            'description' => 'nullable|string|min:100',
            'image' => 'file_attachment'
        ];

        return $this->addTranslatableRules(['title', 'description'], $rules);
    }
}
