<?php

return array(
    'type'         => array(
        'title'   => '开启同步登陆：',
        'type'    => 'checkbox',
        'options' => array(
            'Qq'     => 'Qq',
            'Sina'   => 'Sina',
            'Weixin' => 'Weixin',
        ),
    ),
    'bind'         => array( //配置在表单中的键名 ,这个会是config[title]
        'title'   => '是否开启帐号绑定：', //表单的文字
        'type'    => 'radio', //表单的类型：text、textarea、checkbox、radio、select等
        'options' => array(
            '1' => '是',
            '0' => '否',
        ),
        'value'   => '0',
        'tip'     => '不开启则跳过与本地帐号绑定过程，建议审核时关闭绑定。',
    ),
    'QqKEY'        => array(
        'title' => 'QQ互联APP ID：',
        'type'  => 'text',
        'value' => '',
        'tip'   => '申请地址：http://connect.qq.com',
    ),
    'QqSecret'     => array(
        'title' => 'QQ互联APP KEY：',
        'type'  => 'text',
        'value' => '',
        'tip'   => '申请地址：http://connect.qq.com',
    ),

    'SinaKEY'      => array(
        'title' => '新浪App Key：',
        'type'  => 'text',
        'value' => '',
        'tip'   => '申请地址：http://open.weibo.com/',
    ),
    'SinaSecret'   => array(
        'title' => '新浪App Sercet：',
        'type'  => 'text',
        'value' => '',
        'tip'   => '申请地址：http://open.weibo.com/',
    ),

    'WeixinKEY'    => array(
        'title' => '微信App Key：',
        'type'  => 'text',
        'value' => '',
        'tip'   => '电脑扫码申请地址：https://open.weixin.qq.com/',
    ),
    'WeixinSecret' => array(
        'title' => '微信App Sercet：',
        'type'  => 'text',
        'value' => '',
        'tip'   => '公众号申请地址：https://mp.weixin.qq.com/',
    ),
    'display'      => array(
        'title'   => '微信登录选择方式',
        'type'    => 'radio',
        'options' => array(
            '1' => '电脑扫码登录',
            '0' => '公众号登录',
        ),
        'value'   => '1',
    ),
);
