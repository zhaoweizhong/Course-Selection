<?php

namespace App\Http\Requests\Api;

use Dingo\Api\Http\FormRequest;

class UserRequest extends FormRequest
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
                    'sid'      => 'required|numeric|regex:/^[0-9]{8}$/|unique:users',
                    'password' => 'required|string|min:6',
                    'name'     => 'required|string|max:255',
                    'email'    => 'required|email',
                    'type'     => 'required|numeric|in:0,1'
                ];
                break;
            case 'PATCH':
                $userId = \Auth::guard('api')->id();
                return [
                    'password'   => 'required|string|min:6',
                    'name'       => 'string|max:255',
                    'email'      => 'email',
                    'mobile'     => 'numeric|digits:11',
                    'avatar_url' => 'url',
                ];
                break;
        }
    }
}
