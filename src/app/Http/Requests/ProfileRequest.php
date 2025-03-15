<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'image' => '',
            'name' => ['required', 'max:150'],
            'post' =>  ['required', 'numeric'],
            'address' => 'required',
            'building' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'image.' => '',
            'name.required' => 'ユーザー名を入力してください',
            'name.max' => 'ユーザー名を150字以内で入力してください',
            'post.required' => '郵便番号を入力してください',
            'post.numeric' => '郵便番号は数字で入力してください',
            '' => '',
            'address.required' => '住所を入力してください',
            '' => '',
        ];
    }
}
