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

use think\Cache;
use think\Db;

class Content
{
    /**
     * 栏目列表
     */
    public function category($data)
    {
        //缓存时间
        $cache = (int) $data['cache'];
        //调用数量
        $num = (int) $data['num'];
        $cacheID = to_guid_string($data);
        if ($cache && $array = Cache::get($cacheID)) {
            return $array;
        }
        //栏目ID
        $data['catid'] = intval($data['catid']);
        //设置SQL where 部分
        if (isset($data['where']) && $data['where']) {
            $where = $data['where'];
        }
        $db = Db::name('Category');
        if (isset($data['catid'])) {
            //$where['ismenu'] = 1;
            //$where['parentid'] = $data['catid'];
            if ($where) {
                $where .= " AND `ismenu` = 1 AND `parentid` = '" . intval($data['catid']) . "'";
            } else {
                $where .= " `ismenu` = 1 AND `parentid` = '" . intval($data['catid']) . "'";
            }
        }
        //如果条件不为空，进行查库
        if (!empty($where)) {
            if ($num) {
                $categorys = $db->where($where)->limit($num)->order($data['order'])->select();
            } else {
                $categorys = $db->where($where)->order($data['order'])->select();
            }
        }
        //结果进行缓存
        if ($cache) {
            Cache::set($cacheID, $categorys, $cache);
        }
        return $categorys;
    }

}
