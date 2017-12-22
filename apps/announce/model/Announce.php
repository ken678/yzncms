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

namespace app\announce\model;

use think\Model;

/**
 * 系统公告模型
 * @author 御宅男  <530765310@qq.com>
 */
class Announce extends Model
{
    protected $insert = ['addtime'];
    protected function setAddtimeAttr()
    {
        return time();
    }

}
