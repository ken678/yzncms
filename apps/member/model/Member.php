<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2007 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 御宅男 <530765310@qq.com>
// +----------------------------------------------------------------------

namespace app\member\model;

use think\Model;

/**
 * 模块模型
 * @package app\admin\model
 */
class Member extends Model
{
    //会员模型缓存
    public function member_model_cahce()
    {
        $data = model('content/Models')->getModelAll(2);
        cache("Model_Member", $data);
        return $data;
    }

}
