<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class RuleRequest extends FormRequest
{
    /**
     * 定义验证规则
     */
    protected $rules = [
        'rule_name' => 'required|unique:rule|max:16|min:2',
        'pid' => 'required|numeric',
        'route' => 'required',
        'rule_c' => 'required',
        'rule_a' => 'required',
        'is_show' => 'required|in:1,2',
        'is_verify' => 'required|in:1,2',
        'remark' => 'max:255',
    ];
    /**
     * 定义错误信息
     */
    protected $messages = [
        'rule_name.required' => '权限名称必须填写',
        'rule_name.unique' => '权限名称不得重复',
        'rule_name.max' => '权限名称不得超出16个字符',
        'rule_name.min' => '权限名称不得小于2个字符',

        'pid.required' => '权限父id必须选择',
        'pid.integer' => '权限父id格式不对',

        'route.required' => '路由名称必须填写',
        'rule_c.required' => '控制器名称必须填写',
        'rule_a.required' => '方法名称必须填写',
        'is_show.required' => '是否显示必须选择',
        'is_show.in' => '是否显示格式不对',
        'is_verify.required' => '是否验证必须选择',
        'is_verify.in' => '是否验证格式不对',
        'remark.max' => '备注不得超出255个字符',
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
        // 顶级权限 路由,控制器,方法清空
        $pid = Request::get('pid');
        if($pid == '0'){
            $rules['route'] = '';
            $rules['rule_c'] = '';
            $rules['rule_a'] = '';
        }
        if(Request::isMethod('PATCH')){
            $rules['rule_name'] = 'required|max:16|min:2';
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
