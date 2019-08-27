<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * 定义验证规则
     */
    protected $rules = [
        'amount' => 'required|numeric',
        'bankCode' => 'required',
        'bankAccountNo' => 'required',
        'bankAccountName' => 'required',
    ];
    /**
     * 定义错误信息
     */
    protected $messages = [
        'amount.required' => '金额必须填写',
        'amount.numeric' => '金额格式不对',
        'bankCode.required' => '银行编号必须填写',
        'bankAccountNo.required' => '银行账号必须填写',
        'bankAccountName.required' => '银行持卡人必须填写',
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
