<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'name' => ['required',  'max:255'],
            'brand' => ['max:255'],
            'price' => ['required', 'integer', 'min:0'],
            'detail' => ['required',  'max:255'],
            'img' =>  ['required',  'max:255', 'image', 'mimes:jpg,jpeg,png'],
            'condition_id' => ['required'],
            'categories' => ['required', 'array', 'min:1']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'name.max' => '商品名は255文字以内で入力してください',

            'brand.max' => 'ブランド名は255文字以内で入力してください',

            'price.required' => '商品価格を入力してください',
            'price.integer' => '商品価格は半角の数値で入力してください',
            'price.min' => '商品価格は0円以上で入力してください',

            'detail.required' => '商品説明を入力してください',
            'detail.max' => '商品説明は255文字以内で入力してください',

            'img.required' => '商品画像は必須です',
            'img.max' => '画像ファイルは拡張子含め255文字以内で指定してください',
            'img.mimes' => '画像は jpg または png 形式のみアップロード可能です',

            'condition_id.required' => '商品の状態を入力してください',

            'categories.required' => '商品のカテゴリーを入力してください',
        ];
    }
}
