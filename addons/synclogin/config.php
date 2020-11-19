<?php
return array(
    array(
        'name'    => 'type',
        'title'   => '开启同步登陆：',
        'type'    => 'checkbox',
        'options' => array(
            'Qq'     => 'Qq',
            'Sina'   => 'Sina',
            'Weixin' => 'Weixin',
        ),
        'value'   => '',
    ),
    array(
        'name'    => 'bind',
        'title'   => '是否开启帐号绑定：',
        'type'    => 'radio',
        'options' => array(
            '1' => '是',
            '0' => '否',
        ),
        'value'   => '0',
        'tip'     => '不开启则跳过与本地帐号绑定过程，建议审核时关闭绑定。',
    ),
    array(
        'name'  => 'QqKEY',
        'title' => 'QQ互联APP ID：',
        'type'  => 'text',
        'value' => '',
        'tip'   => '申请地址：http://connect.qq.com',
    ),
    array(
        'name'  => 'QqSecret',
        'title' => 'QQ互联APP KEY：',
        'type'  => 'text',
        'value' => '',
        'tip'   => '申请地址：http://connect.qq.com',
    ),
    array(
        'name'  => 'SinaKEY',
        'title' => '新浪App Key：',
        'type'  => 'text',
        'value' => '',
        'tip'   => '申请地址：http://open.weibo.com/',
    ),
    array(
        'name'  => 'SinaSecret',
        'title' => '新浪App Sercet：',
        'type'  => 'text',
        'value' => '',
        'tip'   => '申请地址：http://open.weibo.com/',
    ),
    array(
        'name'  => 'WeixinKEY',
        'title' => '微信App Key：',
        'type'  => 'text',
        'value' => '',
        'tip'   => '电脑扫码申请地址：https://open.weixin.qq.com/',
    ),
    array(
        'name'  => 'WeixinSecret',
        'title' => '微信App Sercet：',
        'type'  => 'text',
        'value' => '',
        'tip'   => '公众号申请地址：https://mp.weixin.qq.com/',
    ),
    array(
        'name'    => 'display',
        'title'   => '微信登录选择方式',
        'type'    => 'radio',
        'options' => array(
            '1' => '电脑扫码登录',
            '0' => '公众号登录',
        ),
        'value'   => '1',
    ),
);
