<?php

return array(
    0 => array(
        'name'    => 'mail_type',
        'title'   => '邮件发送模式',
        'type'    => 'radio',
        'options' => array(
            1 => 'SMTP',
            2 => 'Mail',
        ),
        'value'   => '1',
    ),
    1 => array(
        'name'  => 'mail_smtp_host',
        'title' => '邮件服务器',
        'type'  => 'text',
        'value' => 'smtp.163.com',
        'tip'   => '错误的配置发送邮件会导致服务器超时',
    ),
    2 => array(
        'name'  => 'mail_smtp_port',
        'title' => '邮件发送端口',
        'type'  => 'text',
        'value' => '465',
        'tip'   => '不加密默认25,SSL默认465,TLS默认587',
    ),
    3 => array(
        'name'    => 'mail_auth',
        'title'   => '身份认证',
        'type'    => 'radio',
        'options' => array(
            0 => '关闭',
            1 => '开启',
        ),
        'value'   => '1',
    ),
    4 => array(
        'name'  => 'mail_smtp_user',
        'title' => '用户名',
        'type'  => 'text',
        'value' => '',
        'tip'   => '',
    ),
    5 => array(
        'name'  => 'mail_smtp_pass',
        'title' => '密码',
        'type'  => 'text',
        'value' => '',
        'tip'   => '注意不是邮箱密码，是授权码',
    ),
    6 => array(
        'name'    => 'mail_verify_type',
        'title'   => '验证方式',
        'type'    => 'radio',
        'options' => array(
            1 => 'TLS',
            2 => 'SSL',
        ),
        'value'   => '2',
    ),
    7 => array(
        'name'  => 'mail_from',
        'title' => '发件人邮箱',
        'type'  => 'text',
        'value' => '',
        'tip'   => '',
    ),
);
