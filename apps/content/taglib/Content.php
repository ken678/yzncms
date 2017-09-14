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
namespace app\content\taglib;

class Content
{
    /**
     * 栏目列表（category）
     */
    public function category($data)
    {
        //栏目ID
        $data['catid'] = intval($data['catid']);
        //设置SQL where 部分
        if (isset($data['where']) && $data['where']) {
            $where['_string'] = $data['where'];
        }
        $db = db('Category');
        $num = (int) $data['num'];
        if (isset($data['catid'])) {
            $where['ismenu'] = 1;
            $where['parentid'] = $data['catid'];
        }
        //如果条件不为空，进行查库
        if (!empty($where)) {
            if ($num) {
                $categorys = $db->where($where)->limit($num)->order($data['order'])->select();
            } else {
                $categorys = $db->where($where)->order($data['order'])->select();
            }
        }
        return $categorys;
    }

}
