<?php

namespace Khonik\Notifications\Requests;

class NotificationCreateRequest extends ApiRequest
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
            'type' => "required|in:push,email,any",
            'title' => "required|string|max:255",
            'text' => "required|string|max:5000"
        ];
    }
}
