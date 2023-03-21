<?php

return [
    [
        'name'  => 'baidu',
        'title' => '百度站长',
        'type'  => 'array',
        'value' => [
            'site'  => '',
            'token' => '',
        ],
        'tip'   => '请前往百度站长平台获取',
    ],
    [
        'name'  => 'shenma',
        'title' => '神马站长',
        'type'  => 'array',
        'value' => [
            'site'      => '',
            'user_name' => '',
            'token'     => '',
        ],
        'tip'   => '请前往神马站长平台获取',
    ],
    [
        'name'    => 'status',
        'title'   => '推送状态',
        'type'    => 'checkbox',
        'options' => [
            'baidu'  => '百度站长',
            'shenma' => '神马搜索',
        ],
        'tip'     => '',
        'value'   => 'baidu,shenma',
    ],
];
