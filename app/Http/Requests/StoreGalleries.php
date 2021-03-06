<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGalleries extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return get_auth() ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {

        return [
            'gallery' => 'required|array',
            'gallery.*' => 'image|mimes:jpg,jpeg,png,gif'
        ];
    }

    public function messages()
    {
        return [
            'gallery' => 'The gallery must be an image.',
            'gallery.*' => 'The gallery must be a file of type:jpg,jpeg,png,gif.'
        ];
    }

}
