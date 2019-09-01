<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnboundRequest extends FormRequest
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
            'mg_id' => 'required|array',
            'gooleToken' => 'required|numeric'
        ];
    }

    /**
     * 自定义错误信息
     */
    public function messages()
    {
        return [
            'mg_id.required' => '请选择管理员',
            'mg_id.array' => '管理员格式不对',
            'gooleToken.required' => '谷歌二次验证不得为空1',
            'gooleToken.numeric' => '格式为数值'
        ];
    }
}
