<?php

namespace addons\signin\model;

use think\Model;

class Signin extends Model
{
    // 表名
    protected $name = 'signin';

    public function user()
    {
        return $this->belongsTo('\app\member\model\Member', "uid", "id");
    }
}
