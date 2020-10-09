<?php

return array(
    'type'      => array(
        'title'   => '分词平台',
        'type'    => 'radio',
        'options' => array(
            'baidu' => '百度',
            'xfyun' => '讯飞',
        ),
        'value'   => 'baidu',
        'tip'     => '',
    ),
    'appid'     => array(
        'title' => '你的 App ID',
        'type'  => 'text',
        'value' => '',
        'tip'   => '',
    ),
    'apikey'    => array(
        'title' => '你的 Api Key',
        'type'  => 'text',
        'value' => '',
        'tip'   => '',
    ),
    'secretkey' => array(
        'title' => '你的 Secret Key',
        'type'  => 'text',
        'value' => '',
        'tip'   => '讯飞不用填写此项',
    ),
    'max'       => array(
        'title' => '最多获取数量',
        'type'  => 'text',
        'value' => '5',
        'tip'   => '',
    ),
);
