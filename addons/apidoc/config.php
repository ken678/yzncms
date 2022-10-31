<?php
return [
    [
        'name'  => 'rewrite',
        'title' => '伪静态',
        'type'  => 'array',
        'value' => [
            'index/index'      => '/apidoc$',
            'index/getConfig'  => '/apidoc/config',
            'index/getList'    => '/apidoc/data',
            'index/verifyAuth' => '/apidoc/auth',
        ],
    ],
];
