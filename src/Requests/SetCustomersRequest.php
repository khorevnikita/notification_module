<?php

namespace Khonik\Notifications\Requests;

class SetCustomersRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "users" => "array|min:1",
            "users.*" => "exists:users,id"
        ];
    }
}
