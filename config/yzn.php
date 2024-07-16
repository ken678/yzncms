<?php
// +----------------------------------------------------------------------
// | YZNCMS设置
// +----------------------------------------------------------------------

return [
    //是否开启前台会员中心
    'user_center'           => true,
    //会员登录验证码
    'user_login_captcha'    => true,
    //会员注册手机验证码
    'user_mobile_verify'    => false,
    //会员注册邮箱验证码
    'user_email_verify'     => false,
    //会员注册密码确认
    'user_password_confirm' => true,
    //会员注册不显示昵称
    'user_remove_nickname'  => false,
    //新注册默认赠送用户积分
    'user_defualt_point'    => 0,
    //新会员注册默认赠送资金
    'user_defualt_amount'   => 0,
    //新会员注册需要管理员审核
    'user_register_verify'  => false,
    //会员头像模式 'default/默认','letter/首字母','gravatar/Gravatar',
    'user_avatar_mode'      => 'default',
    //是否开启VIP会员
    'user_allow_vip'        => true,
    //1元购买积分
    'user_rmb_point_rate'   => 10,

    //登录失败超过10次则1天后重试
    'admin_login_retry'     => true,
    //是否同一账号同一时间只能在一个地方登录
    'admin_login_unique'    => false,
    //是否开启IP变动检测
    'admin_loginip_check'   => false,
    //允许跨域的域名,多个以,分隔
    'cors_request_domain'   => 'localhost,127.0.0.1',
    //是否开启后台自动日志记录
    'auto_record_admin_log' => true,

    //CDN地址
    'cdnurl'                => '',
    //API接口地址
    'api_url'               => 'https://api.yzncms.com',
    //插件启用禁用时是否备份对应的全局文件
    'backup_global_files'   => true,
    //插件纯净模式，插件启用后是否删除插件目录的application、public和assets文件夹
    'addon_pure_mode'       => true,
    /* 系统数据加密设置 */
    'data_auth_key'         => 'Mhc(jk`[t.7?P_Ty=A%41o+S{J390DKpFmvW@E}8', //默认数据加密KEY
];
