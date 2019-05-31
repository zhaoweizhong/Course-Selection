<?php

namespace App\Http\Requests\Api;

use Dingo\Api\Http\FormRequest;

class CourseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        switch($this->method()) {
            case 'POST':
                return [
                    'name'       => 'required|string|max:255',
                    'cid'        => 'required|string|max:10|unique:courses',
                    'credit'     => 'required|numeric',
                    'time'       => 'required|string|max:255',
                    'location'   => 'required|string|max:255',
                    'capacity'   => 'required|numeric',
                ];
                break;
            case 'PATCH':
                return [
                    'name'       => 'string|max:255',
                    'cid'        => 'string|max:10|unique:courses',
                    'credit'     => 'numeric',
                    'time'       => 'string|max:255',
                    'location'   => 'string|max:255',
                    'capacity'   => 'numeric',
                ];
                break;
        }
    }
}
