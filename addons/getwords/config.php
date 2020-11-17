<?php

return array(
    0 => array(
        'name'    => 'type',
        'title'   => '分词平台',
        'type'    => 'radio',
        'options' => array(
            'baidu' => '百度',
            'xfyun' => '讯飞',
        ),
        'value'   => 'baidu',
        'tip'     => '',
    ),
    1 => array(
        'name'  => 'appid',
        'title' => '你的 App ID',
        'type'  => 'text',
        'value' => '',
        'tip'   => '',
    ),
    2 => array(
        'name'  => 'apikey',
        'title' => '你的 Api Key',
        'type'  => 'text',
        'value' => '',
        'tip'   => '',
    ),
    3 => array(
        'name'  => 'secretkey',
        'title' => '你的 Secret Key',
        'type'  => 'text',
        'value' => '',
        'tip'   => '讯飞不用填写此项',
    ),
    4 => array(
        'name'  => 'max',
        'title' => '最多获取数量',
        'type'  => 'text',
        'value' => '5',
        'tip'   => '',
    ),
);
