<?php

return array (
  0 => 
  array (
    'name' => 'type',
    'title' => '开启同步登陆：',
    'type' => 'checkbox',
    'options' => 
    array (
      'Qq' => 'Qq',
      'Sina' => 'Sina',
      'Weixin' => 'Weixin',
    ),
    'value' => 'Qq,Sina,Weixin',
  ),
  1 => 
  array (
    'name' => 'bind',
    'title' => '是否开启帐号绑定：',
    'type' => 'radio',
    'options' => 
    array (
      1 => '是',
      0 => '否',
    ),
    'value' => '1',
    'tip' => '不开启则跳过与本地帐号绑定过程，建议审核时关闭绑定。',
  ),
  2 => 
  array (
    'name' => 'QqKEY',
    'title' => 'QQ互联APP ID：',
    'type' => 'text',
    'value' => '',
    'tip' => '申请地址：http://connect.qq.com',
  ),
  3 => 
  array (
    'name' => 'QqSecret',
    'title' => 'QQ互联APP KEY：',
    'type' => 'text',
    'value' => '',
    'tip' => '申请地址：http://connect.qq.com',
  ),
  4 => 
  array (
    'name' => 'SinaKEY',
    'title' => '新浪App Key：',
    'type' => 'text',
    'value' => '1398656192',
    'tip' => '申请地址：http://open.weibo.com/',
  ),
  5 => 
  array (
    'name' => 'SinaSecret',
    'title' => '新浪App Sercet：',
    'type' => 'text',
    'value' => 'b0a89545bc5cef11ad2f6b603966666a',
    'tip' => '申请地址：http://open.weibo.com/',
  ),
  6 => 
  array (
    'name' => 'WeixinKEY',
    'title' => '微信App Key：',
    'type' => 'text',
    'value' => '',
    'tip' => '电脑扫码申请地址：https://open.weixin.qq.com/',
  ),
  7 => 
  array (
    'name' => 'WeixinSecret',
    'title' => '微信App Sercet：',
    'type' => 'text',
    'value' => '',
    'tip' => '公众号申请地址：https://mp.weixin.qq.com/',
  ),
  8 => 
  array (
    'name' => 'display',
    'title' => '微信登录选择方式',
    'type' => 'radio',
    'options' => 
    array (
      1 => '电脑扫码登录',
      0 => '公众号登录',
    ),
    'value' => '1',
  ),
);
