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
namespace app\content\model;

use think\Model;

/**
 * 单页模型
 */
class Page extends Model
{
    /**
     * 根据栏目ID获取内容
     * @param type $catid 栏目ID
     * @return boolean
     */
    public function getPage($catid)
    {
        if (empty($catid)) {
            return false;
        }
        return $this->where(array('catid' => $catid))->find();
    }
}
