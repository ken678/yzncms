<?php
//前台配置文件
return [
    // +----------------------------------------------------------------------
    // | 模板设置
    // +----------------------------------------------------------------------
    'template' => [
        // 预先加载的标签库
        'taglib_pre_load' => 'app\common\taglib\Yzn',

    ],
    'PAGE_LISTROWS' => '10',
    'VAR_PAGE' => 'page',
    'PAGE_TEMPLATE' => '<span class="all">共有{recordcount}条信息</span><span class="pageindex">{pageindex}/{pagecount}</span>{first}{prev}{liststart}{list}{listend}{next}{last}',

    // +----------------------------------------------------------------------
    // | 会话设置
    // +----------------------------------------------------------------------
    'session' => [
        'id' => '',
        // SESSION_ID的提交变量,解决flash上传跨域
        'var_session_id' => '',
        // SESSION 前缀
        'prefix' => 'yzn_home',
        // 驱动方式 支持redis memcache memcached
        'type' => '',
        // 是否自动开启 SESSION
        'auto_start' => true,
    ],

];
