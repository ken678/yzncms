<?php
// +----------------------------------------------------------------------
// | 应用设置
// +----------------------------------------------------------------------

return [
    // 应用地址
    'app_host'                => env('APP_HOST', ''),
    // 应用的命名空间
    'app_namespace'           => '',
    // 是否启用路由
    'with_route'              => true,
    // 默认应用
    'default_app'             => 'index',
    // 默认时区
    'default_timezone'        => 'Asia/Shanghai',

    // 应用映射（自动多应用模式有效）
    'app_map'                 => [],
    // 域名绑定（自动多应用模式有效）
    'domain_bind'             => [],
    // 禁止URL访问的应用列表（自动多应用模式有效）
    'deny_app_list'           => ['common', 'admin', 'install'],

    // 异常页面的模板文件
    'exception_tmpl'          => app_path() . 'common' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'think_exception.tpl',
    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'   => app_path() . 'common' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'dispatch_jump.tpl',
    'dispatch_error_tmpl'     => app_path() . 'common' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'dispatch_jump.tpl',
    // 配置仅在部署模式下面生效
    'http_exception_template' => [
        404 => app_path() . 'common' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . '404.tpl',
        // 还可以定义其它的HTTP status
        //401 =>  app()->getBasePath() . '401.html',
    ],
    // 错误显示信息,非调试模式有效
    'error_message'           => '页面错误！请稍后再试～',
    // 显示错误信息
    'show_error_msg'          => false,
];
