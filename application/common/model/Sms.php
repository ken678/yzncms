<?php

namespace app\common\model;

use think\Model;

/**
 * 短信验证码
 */
class Sms extends Model
{

    protected $auto = ['ip'];

    public function setIpAttr($value)
    {
        return request()->ip();
    }

}
