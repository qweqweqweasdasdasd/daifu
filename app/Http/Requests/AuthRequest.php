<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
            'mg_name' => 'required|max:16|min:2',
            'password' => 'required|max:16|min:4',
            'gooleToken' => 'required|numeric'
        ];
    }

    /**
     * 自定义错误信息
     */
    public function messages()
    {
        return [
            'mg_name.required' => '管理员名称必须填写!',
            'mg_name.max' => '管理员名称不得超出16个字符!',
            'mg_name.min' => '管理员名称不得小于2个字符!',
            'password.required' => '管理员密码必须填写!',
            'password.max' => '管理员密码不得超出16个字符!',
            'password.min' => '管理员密码不得小于4个字符!',
            'gooleToken.required' => '谷歌二次验证不得为空1',
            //'gooleToken.same' => '谷歌验证必须为6位',
            'gooleToken.numeric' => '格式为数值'
        ];
    }
}
