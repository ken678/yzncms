<?php
return [
    [
        'name'  => 'accessKey',
        'title' => '阿里云OSS的ak',
        'type'  => 'text',
        'value' => '',
        'tip'   => '阿里云OSS的ak',
    ],
    [
        'name'  => 'secrectKey',
        'title' => '阿里云OSS的sk',
        'type'  => 'text',
        'value' => '',
        'tip'   => '阿里云OSS的sk',
    ],
    [
        'name'  => 'bucket',
        'title' => '阿里云OSS空间Bucket名称',
        'type'  => 'text',
        'value' => '',
        'tip'   => '阿里云OSS空间Bucket名称',
    ],
    [
        'name'  => 'endpoint',
        'title' => '地域节点Endpoint',
        'type'  => 'text',
        'value' => '',
        'tip'   => '阿里云OSS地域节点Endpoint',
    ],
    [
        'name'  => 'domain',
        'title' => '空间绑定的域名',
        'type'  => 'text',
        'value' => '',
        'tip'   => '阿里云OSS空间对应绑定的域名，已http://开头，/结尾',
    ],
    [
        'name'  => 'stylename',
        'title' => '图片处理规则名称',
        'type'  => 'text',
        'value' => '',
        'tip'   => '设置后，同时在阿里云oss后台【访问设置】开启原图保护',
    ],
    [
        'name'    => 'separator',
        'title'   => '图片处理自定义分隔符',
        'type'    => 'select',
        'options' => [
            '-' => '-',
            '_' => '_',
            '/' => '/',
            '!' => '!',
        ],
        'value'   => '',
        'tip'     => '需要在阿里云oss后台【访问设置】自定义分隔符打勾',
    ],
];
