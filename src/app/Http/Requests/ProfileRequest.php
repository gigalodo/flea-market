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
            'name' => ['required',  'max:20'],
            'post_code' => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'address' => ['required', 'max:255'],
            'building' => ['max:255'],
            'user_img' => ['max:255', 'image', 'mimes:jpg,jpeg,png'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'お名前を入力してください',
            'name.max' => 'お名前は20文字以内で入力してください',

            'post_code.required' => '郵便番号を入力してください',
            'post_code.regex' => '郵便番号は「XXX-XXXX」の形式で入力してください',

            'address.required' => '住所を入力してください',
            'address.max' => '住所は255文字以内で入力してください',

            'building.max' => '建物名は255文字以内で入力してください',

            'user_img.max' => '画像ファイルは拡張子含め255文字以内で指定してください',
            'user_img.mimes' => '画像は jpg または png 形式のみアップロード可能です',
        ];
    }
}
