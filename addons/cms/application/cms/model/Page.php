<?php
// +----------------------------------------------------------------------
// | Yzncms [ 御宅男工作室 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://yzncms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 御宅男 <530765310@qq.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 单页模型
// +----------------------------------------------------------------------
namespace app\cms\model;

use think\Model;

class Page extends Model
{
    protected $pk = 'catid';
    /**
     * 根据栏目ID获取内容
     *
     * @param  type  $catid  栏目ID
     *
     * @return boolean
     */
    public function getPage($catid, $cache = false)
    {
        if (empty($catid)) {
            return false;
        }
        if (is_numeric($cache)) {
            $cache = (int) $cache;
        }
        return self::get($catid, $cache);
    }
}
