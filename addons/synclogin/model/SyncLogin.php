<?php

namespace addons\synclogin\model;

use think\Model;

class SyncLogin extends Model
{
    protected $autoWriteTimestamp = true;
    public function member()
    {
        return $this->belongsTo('\app\member\model\Member', 'uid', 'id', [], 'LEFT');
    }
}
