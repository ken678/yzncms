<?php

namespace app\common\model;

use think\Model;

/**
 * 邮箱验证码
 */
class Ems extends Model
{

    protected $auto = ['ip'];

    public function setIpAttr($value)
    {
        return request()->ip();
    }

}
