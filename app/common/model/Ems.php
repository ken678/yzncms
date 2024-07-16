<?php

namespace app\common\model;

use think\Model;

/**
 * 邮箱验证码
 */
class Ems extends Model
{
    protected $autoWriteTimestamp = false;

    public function setIpAttr($value)
    {
        return request()->ip();
    }

}
