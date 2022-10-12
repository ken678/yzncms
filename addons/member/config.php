<?php
/**
 * 会员系统，默认配置
 */
return array(
    [
        'name'    => 'allowregister',
        'title'   => '新会员注册',
        'type'    => 'radio',
        'options' => [
            1 => '允许',
            0 => '禁止',
        ],
        'value'   => 1,
    ],
    [
        'name'    => 'registerverify',
        'title'   => '注册审核',
        'type'    => 'radio',
        'options' => [
            1 => '是',
            0 => '否',
        ],
        'value'   => 0,
    ],
    [
        'name'    => 'register_mobile_verify',
        'title'   => '注册手机认证',
        'type'    => 'radio',
        'options' => [
            1 => '是',
            0 => '否',
        ],
        'value'   => 0,
    ],
    [
        'name'    => 'register_email_verify',
        'title'   => '注册邮箱认证',
        'type'    => 'radio',
        'options' => [
            1 => '是',
            0 => '否',
        ],
        'value'   => 0,
    ],
    [
        'name'    => 'openverification',
        'title'   => '登陆验证码',
        'type'    => 'radio',
        'options' => [
            1 => '开启',
            0 => '关闭',
        ],
        'value'   => 1,
    ],
    [
        'name'    => 'password_confirm',
        'title'   => '显示密码确认',
        'type'    => 'radio',
        'options' => [
            1 => '是',
            0 => '否',
        ],
        'value'   => 0,
        'tip'     => '显示或隐藏注册时候的密码确认',
    ],
    [
        'name'    => 'remove_nickname',
        'title'   => '注册不显示昵称',
        'type'    => 'radio',
        'options' => [
            1 => '是',
            0 => '否',
        ],
        'value'   => 0,
    ],
    [
        'name'  => 'rmb_point_rate',
        'title' => '1元购买积分',
        'type'  => 'text',
        'value' => 10,
    ],
    [
        'name'  => 'defualtpoint',
        'title' => '会员默认积分',
        'type'  => 'text',
        'value' => 0,
    ],
    [
        'name'  => 'defualtamount',
        'title' => '会员默认资金',
        'type'  => 'text',
        'value' => 0,
    ],
);
