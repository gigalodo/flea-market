<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
            //支払方法,配送先
            'payment_method' => ['required'],
            'post_code' => ['required'],
            'address' => ['required'],

        ];
    }

    public function messages()
    {
        return [
            'payment_method.required' => '支払方法を入力してください',

            'post_code.required' => '郵便番号を入力してください',

            'address.required' => '住所を入力してください',
        ];
    }
}
