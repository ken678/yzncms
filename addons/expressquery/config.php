<?php

return [
    [
        'name'    => 'provider',
        'title'   => '快递服务商',
        'type'    => 'select',
        'options' => [
            'aliyun' => '阿里云',
            'juhe'   => '聚合数据',
            'kd100'  => '快递100',
            'kdniao' => '快递鸟',
        ],
        'value'   => 'aliyun',
    ],
    [
        'name'  => 'config',
        'title' => '快递配置',
        'type'  => 'array',
        'value' => [
            'app_code' => '76ffeee4cbc6415aa3d6df8d8370a1f6',
        ],
    ],
];
