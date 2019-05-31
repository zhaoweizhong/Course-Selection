<?php

namespace App\Http\Requests\Api;

use Dingo\Api\Http\FormRequest;

class SelectionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'course_id'        => 'required|numeric',
        ];
    }
}
