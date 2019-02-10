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
// | 标签库
// +----------------------------------------------------------------------
namespace app\cms\taglib;

use think\facade\Cache;

class CmsTagLib
{
    /**
     * 栏目标签
     */
    public function Category($data)
    {

        $where = isset($data['where']) ? $data['where'] : "status=1";
        $order = isset($data['order']) ? $data['order'] : 'listorder,id desc';
        //每页显示总数
        $num = isset($data['num']) ? (int) $data['num'] : 10;
        if (isset($data['catid'])) {
            $catid = (int) $data['catid'];
            $where .= empty($where) ? "parentid = " . $catid : " AND parentid = " . $catid;
        }
        //如果条件不为空，进行查库
        if (!empty($where)) {
            $categorys = model('Category')->where($where)->limit($num)->order($data['order'])->select();
            if ($categorys) {
                $categorys = $categorys->toArray();
            }
        }
        return $categorys;
    }

}
