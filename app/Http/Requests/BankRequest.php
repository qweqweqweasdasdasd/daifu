<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class BankRequest extends FormRequest
{
    /**
     * 定义验证规则
     */
    protected $rules = [
        'bankCode' => 'required',
        'bankAccountNo' => 'required|unique:bank',
        'bankAccountName' => 'required|min:2|max:16',
        'bank_status' => 'required|in:1,2'
    ];
    /**
     * 定义错误信息
     */
    protected $messages = [
        'bankCode.required' => '银行编码必须选择',
        'bankAccountNo.required' => '银行账号必须填写',
        'bankAccountNo.unique' => '银行账号重复',
        'bankAccountName.required' => '持卡人名字必须填写',
        'bankAccountName.min' => '持卡人名字不得小于2个字符',
        'bankAccountName.max' => '持卡人名字不得超出16个字符',
        'bank_status.required' => '状态必须填写',
        'bank_status.in' => '状态格式不对'
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
            $rules['bankAccountNo'] = 'required|numeric';
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
