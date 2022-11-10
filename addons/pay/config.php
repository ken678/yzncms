<?php

return [
    [
        'name'    => 'paytypelist',
        'title'   => '支付方式',
        'type'    => 'checkbox',
        'options' => [
            'wechat' => '微信支付',
            'alipay' => '支付宝支付',
        ],
        'value'   => 'wechat,alipay',
    ],
    [
        'name'  => 'wechat',
        'title' => '微信',
        'type'  => 'array',
        'value' => [
            'app_id'     => '',
            'app_secret' => '',
            'mch_id'     => '',
            'key'        => '',
            'mode'       => 0,
            'log'        => 1,
        ],
        'tip'   => '微信参数配置',
    ],
    [
        'name'  => 'alipay',
        'title' => '支付宝',
        'type'  => 'array',
        'value' => [
            'app_id'         => '',
            'private_key'    => '',
            'ali_public_key' => '',
            'mode'           => 0,
            'isper'          => 0,
            'log'            => 1,
        ],
        'tip'   => '支付宝参数配置',
    ],
];
