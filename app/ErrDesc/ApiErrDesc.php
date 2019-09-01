<?php

namespace App\ErrDesc;

/**
 * api 自定义错误信息
 */
class ApiErrDesc
{
    const SERVER_ERR = ['1000','服务器内部错误!'];

    /**
     * API 认证错误
     */
    const USER_AUTH_FAIL = ['2000','管理员认证失败,用户密码或者账号不符!']; 

    const USER_BAN_STATUS = ['2001','管理员状态被禁止使用,请联系超级管理员!'];

    /**
     * 谷歌二次验证
     */
    
    const GOOLE_VERIFY_FAIL = ['2002','谷歌二次验证失败'];

    const GOOLE_BINDING_NO = ['2003','谷歌账号没有绑定'];
    
    /**
     * 公用自定义成错误
     */
    const UPDATE_STATUS_FAIL = ['10000','状态更新失败'];

    /**
     * 角色自定义错误
     */
    const ROLE_SAVE_FAIL = ['3000','角色创建失败'];

    const ROLE_DELETED_FAIL = ['3001','角色删除失败'];

    const ROLE_UPDATE_FAIL = ['3002','角色更新失败'];


    /**
     * 管理自定义错误
     */
    const MANAGER_SAVE_FAIL = ['4000','管理添加失败'];

    const MANAGER_DELETED_FAIL = ['4001','管理员删除失败'];

    const MANAGER_UPDATE_FAIL = ['4002','管理员更新失败'];

    const MANAGER_ID_FAIL = ['4003','管理员不得为空(格式不对)'];

    const MANAGER_GOOLE_TOKEN_FAIL = ['4004','二次验证不得为空或者不为6位数字'];

    const MANAGER_PASSWORD_NOT_EMPTY = ['4005','旧密码,新密码,确认密码不得为空'];

    const MANAGER_OLD_PASSWORD_ERROR = ['4006','旧密码不对'];

    const MANAGER_OLD_NEW_UNLIKE = ['4007','新旧密码不一致'];
    /**
     * 权限自定义错误
     */
    const RULE_SAVE_FAIL = ['5000','权限添加失败'];

    const RULE_DELETED_FAIL = ['5001','权限删除失败'];
    
    const RULE_UPDATE_FAIL = ['5003','更新失败'];

    /**
     * 银行自定义错误
     */
    const BANK_SAVE_FAIL = ['6000','银行添加失败'];

    const BANK_DELETED_FAIL = ['6002','银行删除失败'];

    const BANK_UPDATE_FAIL = ['6001','银行更新失败'];
    
    /**
     * 订单自定义错误
     */
    const ORDER_SAVE_FAIL = ['7000','订单添加失败'];

    const RECHECK_UNIQU = ['7001','下发60s内不得重复点击'];
    /**
     * 请求接口之后参数修改参数失败
     */
    const REQUEST_FAIL = ['8000','请求接口之后参数修改参数失败'];


}