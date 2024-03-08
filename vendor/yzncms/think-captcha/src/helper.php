<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------
use think\captcha\facade\Captcha;
use think\facade\Route;

Route::get('captcha/[:config]', "\\think\\captcha\\CaptchaController@index");

Validate::extend('captcha', function ($value) {
    return captcha_check($value);
});

Validate::setTypeMsg('captcha', ':attribute错误!');

/**
 * @param string $config
 * @return \think\Response
 */
function captcha($config = null)
{
    return Captcha::create($config);
}

/**
 * @param $config
 * @return string
 */
function captcha_src($config = null)
{
    return Url::build('/captcha' . ($config ? "/{$config}" : ''));
}

/**
 * @param $id
 * @return mixed
 */
function captcha_img($id = '', $domid = ''): string
{
    $src = captcha_src($id);
    $domid = empty($domid) ? $domid : "id='" . $domid . "'";
    return "<img src='{$src}' alt='captcha' " . $domid . " onclick='this.src=\"{$src}?\"+Math.random();' />";
}

/**
 * @param string $value
 * @return bool
 */
function captcha_check($value)
{
    return Captcha::check($value);
}
