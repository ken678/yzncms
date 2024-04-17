<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 应用设置
// +----------------------------------------------------------------------
use think\facade\Env;
error_reporting(E_ALL ^ E_NOTICE);
return [
    // 应用名称
    'app_name'                => '',
    // 应用地址
    'app_host'                => '',
    // 应用调试模式
    'app_debug'               => Env::get('app.debug', false),
    // 应用Trace
    'app_trace'               => Env::get('app.trace', false),
    // 应用模式状态
    'app_status'              => '',
    // 入口自动绑定模块
    'auto_bind_module'        => false,
    // 注册的根命名空间
    'root_namespace'          => ['addons' => ROOT_PATH . 'addons/'],
    // 默认输出类型
    'default_return_type'     => 'html',
    // 默认AJAX 数据返回格式,可选json xml ...
    'default_ajax_return'     => 'json',
    // 默认JSONP格式返回的处理方法
    'default_jsonp_handler'   => 'jsonpReturn',
    // 默认JSONP处理方法
    'var_jsonp_handler'       => 'callback',
    // 默认时区
    'default_timezone'        => 'PRC',
    // 是否开启多语言
    'lang_switch_on'          => false,
    // 默认语言
    'default_lang'            => 'zh-cn',
    // 默认验证器
    'default_validate'        => '',

    // +----------------------------------------------------------------------
    // | 模块设置
    // +----------------------------------------------------------------------

    // 默认模块名
    'default_module'          => 'index',
    // 禁止访问模块
    'deny_module_list'        => ['common'],
    // 默认控制器名
    'default_controller'      => 'Index',
    // 默认操作名
    'default_action'          => 'index',
    // 默认的空模块名
    'empty_module'            => 'error',
    // 默认的空控制器名
    'empty_controller'        => 'Error',
    // 操作方法前缀
    'use_action_prefix'       => false,
    // 操作方法后缀
    'action_suffix'           => '',
    // 自动搜索控制器
    'controller_auto_search'  => false,
    // 是否支持多模块
    'app_multi_module'        => true,
    // 应用类库后缀
    'class_suffix'            => false,
    // 控制器类后缀
    'controller_suffix'       => false,

    // +----------------------------------------------------------------------
    // | URL设置
    // +----------------------------------------------------------------------

    // 默认全局过滤方法 用逗号分隔多个
    'default_filter'          => '',
    // PATHINFO变量名 用于兼容模式
    'var_pathinfo'            => 's',
    // 兼容PATH_INFO获取
    'pathinfo_fetch'          => ['ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL'],
    // HTTPS代理标识
    'https_agent_name'        => '',
    // URL伪静态后缀
    'url_html_suffix'         => 'html',
    // 域名根，如thinkphp.cn
    'url_domain_root'         => '',
    // IP代理获取标识 如果获取客户端IP异常，请设置false
    'http_agent_ip'           => 'HTTP_X_REAL_IP',
    // 是否自动转换URL中的控制器和操作名
    'url_convert'             => true,
    // 默认的访问控制器层
    'url_controller_layer'    => 'controller',
    // 表单请求类型伪装变量
    'var_method'              => '_method',
    // 表单ajax伪装变量
    'var_ajax'                => '_ajax',
    // 表单pjax伪装变量
    'var_pjax'                => '_pjax',
    // 是否开启请求缓存 true自动缓存 支持设置请求缓存规则
    'request_cache'           => false,
    // 请求缓存有效期
    'request_cache_expire'    => null,
    // 全局请求缓存排除规则
    'request_cache_except'    => [],

    // +----------------------------------------------------------------------
    // | 路由设置
    // +----------------------------------------------------------------------

    // pathinfo分隔符
    'pathinfo_depr'           => '/',
    // URL普通方式参数 用于自动生成
    'url_common_param'        => false,
    // URL参数方式 0 按名称成对解析 1 按顺序解析
    'url_param_type'          => 0,
    // 是否开启路由延迟解析
    'url_lazy_route'          => true,
    // 是否强制使用路由
    'url_route_must'          => false,
    // 路由是否完全匹配
    'route_complete_match'    => true,
    // 使用注解路由
    'route_annotation'        => false,
    // 默认的路由变量规则
    'default_route_pattern'   => '[\w\.]+',

    // +----------------------------------------------------------------------
    // | 异常及错误设置
    // +----------------------------------------------------------------------

    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'   => APP_PATH . 'common' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'dispatch_jump.tpl',
    'dispatch_error_tmpl'     => APP_PATH . 'common' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'dispatch_jump.tpl',
    // 异常页面的模板文件
    'exception_tmpl'          => APP_PATH . 'common' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'think_exception.tpl',
    // 配置仅在部署模式下面生效
    'http_exception_template' => [
        404 => APP_PATH . 'common' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . '404.tpl',
        // 还可以定义其它的HTTP status
        //401 =>  APP_PATH . '401.html',
    ],
    // 错误显示信息,非调试模式有效
    'error_message'           => '页面错误！请稍后再试～',
    // 显示错误信息
    'show_error_msg'          => false,
    // 异常处理handle类 留空使用 \think\exception\Handle
    'exception_handle'        => '',

    // +----------------------------------------------------------------------
    // | YznCMS配置项
    // +----------------------------------------------------------------------

    //公开路径
    'public_url'              => ROOT_URL . (defined('IF_PUBLIC') ? '' : 'public/'),
    /* 系统数据加密设置 */
    'data_auth_key'           => 'Mhc(jk`[t.7?P_Ty=A%41o+S{J390DKpFmvW@E}8', //默认数据加密KEY
    //登录失败超过10次则1天后重试
    'login_failure_retry'     => true,
    //是否同一账号同一时间只能在一个地方登录
    'login_unique'            => false,
    //是否开启IP变动检测
    'loginip_check'           => false,
    //插件启用禁用时是否备份对应的全局文件
    'backup_global_files'     => true,
    //插件纯净模式，插件启用后是否删除插件目录的application、public和assets文件夹
    'addon_pure_mode'         => true,
    //允许跨域的域名,多个以,分隔
    'cors_request_domain'     => 'localhost,127.0.0.1',
    //是否开启后台自动日志记录
    'auto_record_admin_log'   => true,
    //文件保存格式
    'savekey'                 => 'uploads/{dir}/{year}{mon}{day}/{filemd5}{.suffix}',
    //是否支持分片上传(文件按钮)
    'chunking'                => false,
    //默认分片大小5M
    'chunksize'               => 5242880,
    //CDN地址
    'cdnurl'                  => '',
    //API接口地址
    'api_url'                 => 'https://api.yzncms.com',
];
