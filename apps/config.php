<?php
error_reporting(E_ERROR | E_PARSE );
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    'USER_MAX_CACHE'     => 1000, //最大缓存用户数
    'USER_ADMINISTRATOR' => 1, //管理员用户ID
    // +----------------------------------------------------------------------
    // | 应用设置
    // +----------------------------------------------------------------------
    // 应用命名空间
    'app_namespace'          => 'app',
    // 应用调试模式
    'app_debug'              => true,
    // 应用Trace
    'app_trace'              => true,

    // +----------------------------------------------------------------------
    // | 模块设置
    // +----------------------------------------------------------------------
    // 默认模块名
    'default_module'         => 'home',
    // 禁止访问模块
    'deny_module_list'       => ['common','runtime'],

    // 默认跳转页面对应的模板文件
    'dispatch_error_tmpl'     =>  APP_PATH .'admin'. DS .'view' . DS . 'public' . DS . 'dispatch_jump.tpl', // 默认错误跳转对应的模板文件
    'dispatch_success_tmpl'   =>  APP_PATH .'admin'. DS .'view' . DS . 'public' . DS . 'dispatch_jump.tpl', // 默认成功跳转对应的模板文件

];
