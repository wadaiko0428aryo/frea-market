<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SellRequest extends FormRequest
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
            'name' => 'required',
            'price' => 'required | numeric| min:1',
            'description' => 'required | max:255',
            'image' => 'required | image | mimes:jpeg,png,jpg,gif | max:5120',
            'condition' => 'required',
            'brand' => 'required',
            'category' => 'required | array',
            'category.*' => 'string|exists:categories,category',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'price.required' => '販売価格を入力してください',
            'price.numeric' => '数字で入力してください',
            'price.min' => '1円以上で入力してください',
            'description.max' => '255文字以内で入力してください',
            'description.required' => '商品説明を入力してください',
            'image.required' => '商品画像を選択してください',
            'image.mimes' => '画像はjpeg,png,jpg,gif形式のみ対応しています',
            'image.max' => 'サイズが5MBまでの画像を選択してください',
            'condition.required' => '商品の状態を選択してください',
            'brand.required' => 'ブランド名を入力してください',
            'category.required' => 'カテゴリーを選択してください',
            'category.array' => 'カテゴリの形式が正しくありません。',
            'category.*.string' => 'カテゴリの値が正しくありません。',
            'category.*.exists' => '選択されたカテゴリは存在しません。',
            'name.required' => '商品名を入力してください',
        ];
    }
}
