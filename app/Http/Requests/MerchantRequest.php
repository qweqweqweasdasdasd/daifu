<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class MerchantRequest extends FormRequest
{
    /**
     * 定义验证规则
     */
    protected $rules = [
        'mer_name' => 'required|unique:merchant|max:20',
        'sign' => 'required|max:100|alpha_num',
        'merchant_id' => 'required|numeric',
        'mer_status' => 'required|in:1,2',
        'desc' => 'max:255'
    ];
    /**
     * 定义错误信息
     */
    protected $messages = [
        'mer_name.required' => '商户名称必须添写!',
        'mer_name.unique' => '商户名称不得重复!',
        'mer_name.max' => '商户名称不得超出20个字符!',
        'sign.required' => '签名必须填写!',
        'sign.max' => '签名不得超出100个字符!',
        'sign.alpha_num' => '签名是数字和字母混合体!',
        'merchant_id.required' => '商户ID必须填写!',
        'merchant_id.max' => '商户ID不得超出20个字符!',
        'merchant_id.numeric' => '商户ID格式为数字!',
        'mer_status.required' => '商户状态必须选择!',
        'mer_status.in' => '商户状态是1或者2',
        'desc.max' => '商户备注不得超出255个字符!'
    ];

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
        $rules = $this->rules;
        if(Request::isMethod('PATCH')){
            
        }
        return $rules;
    }

    /**
     * 自定义错误
     */
    public function messages()
    {
        $messages = $this->messages;

        return $messages;
    }
}
