<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
            'post' => ['required', 'numeric'],
            'address' => 'required',
            'building' => 'nullable',
        ];
    }

        public function messages()
    {
        return [
            'post.required' =>'郵便番号を入力してください',
            'post.numeric' =>'郵便番号は数字で入力してください',
            'address.required' => '住所を入力してください',
        ];
    }
}
