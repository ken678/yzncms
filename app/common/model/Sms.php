<?php

namespace app\common\model;

use think\Model;

/**
 * 短信验证码
 */
class Sms extends Model
{
    protected $autoWriteTimestamp = false;

    public function setIpAttr($value)
    {
        return request()->ip();
    }

}
