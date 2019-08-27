<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    /**
     * 定义验证规则
     */
    protected $rules = [
        'r_name' => 'required|unique:role|max:16|min:2',
        'role_status' => 'required|in:1,2',
    ];
    /**
     * 定义错误信息
     */
    protected $messages = [
        'r_name.required' => '角色名称必须填写',
        'r_name.unique' => '角色名称不得重复',
        'r_name.max' => '角色名称不得超出16个字符',
        'r_name.min' => '角色名称不得小于2个字符',
        'role_status.required' => '角色状态必须选择',
        'role_status.in' => '角色状态在给定的范围内',
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
            $rules['r_name'] = 'required|max:16|min:2';
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
