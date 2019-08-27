<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class ManagerRequest extends FormRequest
{
    /**
     * 定义验证规则
     */
    protected $rules = [
        'mg_name' => 'required|unique:mg_name|max:16|min:2',
        'mg_email' => 'required|email',
        'mg_status' => 'required|in:1,2',
        'role_ids' => 'required|array',
        'password' => 'required|max:16|min:4|confirmed'
    ];
    /**
     * 定义错误信息
     */
    protected $messages = [
        'mg_name.required' => '管理员名称必须填写',
        'mg_name.unique' => '管理员不得重复',
        'mg_name.max' => '管理员不得超出16个字符',
        'mg_name.min' => '管理员不得小于2个字符',
        'mg_email.required' => '邮箱必须填写',
        'mg_email.email' => '邮箱格式不对',  
        'mg_status.required' => '状态必须存在',
        'mg_status.in' => '状态格式不对',   
        'role_ids.required' => '角色必须填写',
        'role_ids.array' => '角色格式不对', 
        'password.required' => '密码必须填写',
        'password.max' => '密码不得超出16个字符',
        'password.min' => '密码不得小于2个字符',
        'password.confirmed' => '确认密码和输入密码不一致'
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
            $rules['mg_name'] = 'required|max:16|min:2';
            $rules['password'] = '';
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
