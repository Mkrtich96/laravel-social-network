<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreComments extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (get_auth()) ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'on_post'   =>  'required|exists:posts,id',
            'comment'   =>  'required|regex:/^([a-zA-Z1-9\.]+)$/',
            'from_user' =>  'required|exists:users,id',
        ];
    }
}
