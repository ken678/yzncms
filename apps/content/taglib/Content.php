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

use think\Db;

class Content
{
    /**
     * 栏目列表（category）
     * 参数名  是否必须  默认值  说明
     * catid   否        0       调用该栏目下的所有栏目 ，默认0，调用一级栏目
     * where   否        null    sql查询条件
     * order   否        null    排序方式、一般按照listorder ASC排序，即栏目的添加顺序
     * cache   否        null    数据缓存时间
     * num     否        null    查询数量
     */
    public function category($data)
    {
        //缓存时间
        $cache = (int) $data['cache'];
        $cacheID = to_guid_string($data);
        if ($cache && $array = cache($cacheID)) {
            return $array;
        }
        //栏目ID
        $data['catid'] = intval($data['catid']);
        //设置SQL where 部分
        if (isset($data['where']) && $data['where']) {
            $where['_string'] = $data['where'];
        }
        $db = Db::name('Category');
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
        //结果进行缓存
        if ($cache) {
            cache($cacheID, $categorys, $cache);
        }
        return $categorys;
    }

}
