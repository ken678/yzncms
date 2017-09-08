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
namespace app\common\model;

use think\Model;

class Module extends Model
{
    /**
     * 更新缓存
     * @return type
     */
    public function module_cache() {
        $data = $this->column(true, 'module');
        if (empty($data)) {
            return false;
        }
        $module = array();
        foreach ($data as $v) {
            $module[$v['module']] = $v;
        }
        cache('Module', $module);
        return $module;
    }


}
